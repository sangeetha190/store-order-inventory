<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmation implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $orderId
    ) {}

    public function handle(): void
    {
        $order = Order::with(['customer', 'items.product'])
            ->find($this->orderId);

        if (!$order) {
            Log::warning('Order confirmation skipped: order not found.', [
                'order_id' => $this->orderId,
            ]);

            return;
        }

        Log::info('Order confirmation email simulated.', [
            'order_id' => $order->id,
            'customer_name' => $order->customer->name,
            'customer_email' => $order->customer->email,
            'grand_total' => $order->grand_total,
        ]);
    }
}
