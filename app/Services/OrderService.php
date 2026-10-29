<?php

namespace App\Services;

use App\Jobs\SendOrderConfirmation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
// use App\Jobs\SendOrderConfirmation;
class OrderService
{
    public function create(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $customer = Customer::firstOrCreate(
                ['email' => $data['customer']['email']],
                ['name' => $data['customer']['name']]
            );

            $items = $data['items'];

            // Lock products in a consistent order to reduce deadlocks.
            usort(
                $items,
                fn(array $a, array $b) =>
                $a['product_id'] <=> $b['product_id']
            );

            $subtotal = 0;
            $taxTotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];

                if ($product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => [
                            "Insufficient stock for {$product->name}.",
                        ],
                    ]);
                }

                $unitPrice = (float) $product->price;
                $taxPercentage = (float) $product->tax_percentage;

                $lineSubtotal = round($unitPrice * $quantity, 2);
                $lineTax = round(
                    $lineSubtotal * $taxPercentage / 100,
                    2
                );
                $lineTotal = round($lineSubtotal + $lineTax, 2);

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'tax_percentage' => $product->tax_percentage,
                    'line_subtotal' => number_format(
                        $lineSubtotal,
                        2,
                        '.',
                        ''
                    ),
                    'tax_amount' => number_format(
                        $lineTax,
                        2,
                        '.',
                        ''
                    ),
                    'line_total' => number_format(
                        $lineTotal,
                        2,
                        '.',
                        ''
                    ),
                ];

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;

                $product->decrement('stock_quantity', $quantity);
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'tax_amount' => number_format($taxTotal, 2, '.', ''),
                'grand_total' => number_format(
                    $subtotal + $taxTotal,
                    2,
                    '.',
                    ''
                ),
                'status' => 'completed',
            ]);

            $order->items()->createMany($orderItems);


            return $order->load(['customer', 'items.product']);
        });

        // Dispatch only after the transaction commits.
        SendOrderConfirmation::dispatch($order->id);

        return $order;
    }
}
