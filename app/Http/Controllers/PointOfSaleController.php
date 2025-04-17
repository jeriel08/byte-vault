<?php

namespace App\Http\Controllers;

use App\Models\PointOfSale;
use App\Models\Orderline;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointOfSaleController extends Controller
{
    public function products()
    {
        $categories = PointOfSale::getActiveCategories();
        $brands = PointOfSale::getActiveBrands();
        $products = DB::table('products')
            ->join('brands', 'products.brandID', '=', 'brands.brandID')
            ->join('categories', 'products.categoryID', '=', 'categories.categoryID')
            ->where('products.productStatus', 'Active')
            ->where('products.price', '>', 0)
            ->select('products.productID', 'products.productName', 'products.productDescription', 'products.price', 'products.brandID', 'products.categoryID', 'brands.brandName', 'categories.categoryName')
            ->get();

        $employee = Auth::user();

        return view('employee.products', compact('categories', 'brands', 'products', 'employee'));
    }

    public function sales()
    {
        $orders = PointOfSale::with('orderLines.product')->get();
        return view('employee.sales', compact('orders'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount_received' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.productID' => 'required|exists:products,productID',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::firstOrCreate(
                ['name' => $request->customer_name]
            );

            $order = PointOfSale::create([
                'customerID' => $customer->customerID,
                'total_items' => count($request->items),
                'payment_status' => 'cash',
                'created_by' => Auth::user()->employeeID,
                'created_at' => now(),
                'amount_received' => $request->amount_received,
                'change' => $request->amount_received - $request->grand_total,
                'total' => $request->grand_total,
            ]);

            foreach ($request->items as $item) {
                Orderline::create([
                    'productID' => $item['productID'],
                    'orderID' => $order->orderID,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                ]);

                $product = Product::find($item['productID']);
                $product->stockQuantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order->orderID]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error placing order: ' . $e->getMessage()], 500);
        }
    }
}