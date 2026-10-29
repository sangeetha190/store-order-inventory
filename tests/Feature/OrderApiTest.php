<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation_calculates_totals_and_deducts_stock(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'price' => 100.00,
            'tax_percentage' => 18.00,
            'stock_quantity' => 10,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer' => [
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
            ],
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Order created successfully.')
            ->assertJsonPath('data.subtotal', '200.00')
            ->assertJsonPath('data.tax_amount', '36.00')
            ->assertJsonPath('data.grand_total', '236.00');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        Queue::assertPushed(SendOrderConfirmation::class);
    }

    public function test_insufficient_stock_rejects_order_without_deducting_stock(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'price' => 100.00,
            'tax_percentage' => 18.00,
            'stock_quantity' => 1,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer' => [
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
            ],
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('items');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 1,
        ]);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        Queue::assertNothingPushed();
    }

    public function test_order_request_validates_customer_and_items(): void
    {
        $response = $this->postJson('/api/orders', [
            'customer' => [
                'name' => '',
                'email' => 'invalid-email',
            ],
            'items' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'customer.name',
                'customer.email',
                'items',
            ]);
    }

    public function test_customer_can_retrieve_order_history_by_email(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'history@example.com',
        ]);

        $product = Product::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'tax_percentage' => $product->tax_percentage,
            'line_subtotal' => $product->price,
            'tax_amount' => 0,
            'line_total' => $product->price,
        ]);

        $response = $this->getJson(
            '/api/customers/order-history?email=history@example.com'
        );

        $response->assertOk()
            ->assertJsonPath('customer.email', 'history@example.com')
            ->assertJsonCount(1, 'orders');
    }

    public function test_low_stock_api_respects_threshold(): void
    {
        Product::factory()->create(['stock_quantity' => 3]);
        Product::factory()->create(['stock_quantity' => 8]);
        Product::factory()->create(['stock_quantity' => 15]);

        $response = $this->getJson('/api/products/low-stock?threshold=10');

        $response->assertOk()
            ->assertJsonPath('threshold', 10)
            ->assertJsonPath('count', 2);
    }
}
