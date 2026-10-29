<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('customer')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }
}
