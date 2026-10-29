<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(
        StoreOrderRequest $request,
        OrderService $orderService
    ): JsonResponse {
        $order = $orderService->create($request->validated());

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function customerHistory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $customer = Customer::where('email', $validated['email'])->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }

        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return response()->json([
            'customer' => $customer,
            'orders' => $orders,
        ]);
    }

    public function lowStock(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'threshold' => ['sometimes', 'integer', 'min:0', 'max:100000'],
        ]);

        $threshold = (int) ($validated['threshold'] ?? 10);

        $products = Product::where('stock_quantity', '<', $threshold)
            ->orderBy('stock_quantity')
            ->get();

        return response()->json([
            'threshold' => $threshold,
            'count' => $products->count(),
            'products' => $products,
        ]);
    }
}
