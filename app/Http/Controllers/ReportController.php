<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function inventoryReport()
    {
        // Fetch all products
        $products = Product::all();

        // Calculate summary stats
        $totalProducts = $products->count();
        $totalValue = $products->sum(function ($product) {
            return $product->stockQuantity * $product->price;
        });
        $lowStockCount = $products->where('stockQuantity', '<', 5)->count();

        // Pass data to the view
        return view('admin.reports.inventory', compact('products', 'totalProducts', 'totalValue', 'lowStockCount'));
    }
}
