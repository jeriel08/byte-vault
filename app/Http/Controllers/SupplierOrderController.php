<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierOrderDetail;
use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $supplierOrders = SupplierOrder::with('supplier')->get();
        return view('supplier_orders.index', compact('supplierOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $suppliers = Supplier::where('supplierStatus', 'Active')->get();
        $products = Product::where('productStatus', 'Active')->get();
        return view('supplier_orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'supplierID' => 'required|exists:suppliers,supplierID',
            'orderDate' => 'required|date',
            'expectedDeliveryDate' => 'nullable|date|after_or_equal:orderDate',
            'details' => 'required|array',
            'details.*.productID' => 'required|exists:products,productID',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unitCost' => 'required|numeric|min:0',
        ]);

        $supplierOrder = SupplierOrder::create([
            'supplierID' => $request->supplierID,
            'orderDate' => $request->orderDate,
            'expectedDeliveryDate' => $request->expectedDeliveryDate,
            'status' => 'Pending',
            'totalCost' => 0, // Will be calculated later
            'created_by' => auth()->id(),
        ]);

        $totalCost = 0;
        foreach ($request->details as $detail) {
            $subtotal = $detail['quantity'] * $detail['unitCost'];
            $totalCost += $subtotal;

            SupplierOrderDetail::create([
                'supplierOrderID' => $supplierOrder->supplierOrderID,
                'productID' => $detail['productID'],
                'quantity' => $detail['quantity'],
                'unitCost' => $detail['unitCost'],
                'receivedQuantity' => 0,
                'status' => 'Pending',
            ]);
        }

        $supplierOrder->update(['totalCost' => $totalCost]);

        return redirect()->route('supplier_orders.index')->with('success', 'Supplier order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierOrder $supplierOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierOrder $supplierOrder)
    {
        //
        $suppliers = Supplier::where('supplierStatus', 'Active')->get();
        $products = Product::where('productStatus', 'Active')->get();
        return view('supplier_orders.edit', compact('supplierOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierOrder $supplierOrderID)
    {
        //
        $supplierOrder = SupplierOrder::findOrFail($supplierOrderID);

        $request->validate([
            'status' => 'required|in:Pending,Cancelled,Received',
        ]);

        $supplierOrder->update([
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        // Update product stock and price when status changes to Received
        if ($request->status === 'Received') {
            $details = $supplierOrder->details;
            foreach ($details as $detail) {
                $product = Product::find($detail->productID);
                if ($product) {
                    $product->update([
                        'stockQuantity' => $product->stockQuantity + $detail->quantity,
                        'price' => $detail->unitCost, // Updates price to latest unit cost
                        'updated_by' => auth()->id(),
                    ]);
                    $detail->update([
                        'receivedQuantity' => $detail->quantity,
                        'status' => 'Received',
                    ]);
                }
            }
        }

        return redirect()->route('supplier_orders.index')->with('success', 'Supplier order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierOrder $supplierOrder)
    {
        //
    }
}
