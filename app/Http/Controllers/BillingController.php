<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();

        $lowStockThreshold = (int) request('threshold', 10);

        $lowStockProducts = \App\Models\Product::where(
            'stock_quantity',
            '<',
            $lowStockThreshold
        )->orderBy('stock_quantity')->get();

        return view('billing.create', compact(
            'products',
            'lowStockProducts',
            'lowStockThreshold'
        ));
    }
}
