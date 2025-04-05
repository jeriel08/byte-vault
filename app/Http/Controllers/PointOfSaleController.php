<?php

namespace App\Http\Controllers;

use App\Models\PointOfSale;
use App\Models\Orderline;
use App\Models\Product;
use Illuminate\Http\Request;

class PointOfSaleController extends Controller
{
    public function products()
    {
        $products = Product::all(); // Fetch all products to display
        return view('employee.products', compact('products'));
    }

    public function sales()
    {
        $orders = PointOfSale::with('orderLines.product')->get(); // Fetch orders for sales history
        return view('employee.sales', compact('orders'));
    }
}
