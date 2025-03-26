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
    public function index(Request $request)
    {
        // This method likely already works with filters; just ensure it uses the new date fields instead of status
        $query = SupplierOrder::query();

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplierID', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('orderDate', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('orderDate', '<=', $request->date_to);
        }

        // Sort by order date
        $sortBy = $request->input('sort_by', 'date_desc');
        $query->orderBy('orderDate', $sortBy === 'date_asc' ? 'asc' : 'desc');

        $supplierOrders = $query->get();
        $suppliers = Supplier::all(); // Assuming a Supplier model exists

        return view('supplier_orders.index', compact('supplierOrders', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::where('supplierStatus', 'Active')->get();
        $products = Product::where('productStatus', 'Active')->get();
        $reorderOrder = null;

        if ($request->has('reorder')) {
            $reorderOrder = SupplierOrder::with('details.product')->findOrFail($request->reorder);
        }

        return view('supplier_orders.create', compact('suppliers', 'products', 'reorderOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplierID' => 'required|exists:suppliers,supplierID',
            'orderDate' => 'required|date',
            'expectedDeliveryDate' => 'nullable|date|after_or_equal:orderDate',
            'details' => 'required|array',
            'details.*.productID' => 'required|exists:products,productID',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unitCost' => 'required|numeric|min:0',
        ]);

        $totalCost = collect($request->details)->sum(function ($detail) {
            return $detail['quantity'] * $detail['unitCost'];
        });

        $supplierOrder = SupplierOrder::create([
            'supplierID' => $request->supplierID,
            'orderDate' => $request->orderDate,
            'expectedDeliveryDate' => $request->expectedDeliveryDate,
            'totalCost' => $totalCost,
            'orderPlacedDate' => now(), // Set when order is placed
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);

        foreach ($request->details as $detail) {
            SupplierOrderDetail::create([
                'supplierOrderID' => $supplierOrder->supplierOrderID,
                'productID' => $detail['productID'],
                'quantity' => $detail['quantity'],
                'unitCost' => $detail['unitCost'],
                'receivedQuantity' => 0, // Initially 0, updated when received
            ]);
        }

        return redirect()->route('supplier_orders.index')->with('success', 'Supplier order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierOrder $supplierOrder)
    {
        //
        $supplierOrder->load('supplier', 'details.product');
        return view('supplier_orders.show', compact('supplierOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierOrder $supplierOrder)
    {
        //
        $suppliers = Supplier::where('supplierStatus', 'Active')->get();
        $products = Product::where('productStatus', 'Active')->get();
        $supplierOrder->load('details.product', 'supplier');
        return view('supplier_orders.edit', compact('supplierOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $supplierOrderID)
    {
        $supplierOrder = SupplierOrder::findOrFail($supplierOrderID);

        // Handle "Receive" action from index.blade.php dropdown
        if ($request->has('markAsReceived')) {
            // Prevent receiving an already received or cancelled order
            if ($supplierOrder->receivedDate || $supplierOrder->cancelledDate) {
                return redirect()->route('supplier_orders.index')->with('error', 'This order has already been received or cancelled.');
            }

            $supplierOrder->update([
                'receivedDate' => now(),
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);

            // Update receivedQuantity and product stock/price
            foreach ($supplierOrder->details as $detail) {
                // Set receivedQuantity to match quantity (no partial receiving)
                $detail->update(['receivedQuantity' => $detail->quantity]);

                // Update the product's stock and price
                $product = Product::find($detail->productID);
                if ($product) {
                    $product->update([
                        'stockQuantity' => $product->stockQuantity + $detail->quantity,
                        'price' => $detail->unitCost, // Latest price from supplier order
                    ]);
                }
            }

            return redirect()->route('supplier_orders.index')->with('success', 'Supplier order marked as received, stock and prices updated.');
        }

        // Handle "Cancel" action from index.blade.php dropdown
        if ($request->has('markAsCancelled')) {
            // Prevent cancelling an already received or cancelled order
            if ($supplierOrder->receivedDate || $supplierOrder->cancelledDate) {
                return redirect()->route('supplier_orders.index')->with('error', 'This order has already been received or cancelled.');
            }

            $supplierOrder->update([
                'cancelledDate' => now(),
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);

            return redirect()->route('supplier_orders.index')->with('success', 'Supplier order marked as cancelled.');
        }

        // Handle regular updates from edit.blade.php
        $validated = $request->validate([
            'supplierID' => 'required|exists:suppliers,supplierID',
            'orderDate' => 'required|date',
            'expectedDeliveryDate' => 'nullable|date|after_or_equal:orderDate',
            'details' => 'required|array',
            'details.*.supplierOrderDetailID' => 'sometimes|exists:supplier_order_details,supplierOrderDetailID',
            'details.*.productID' => 'required|exists:products,productID',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unitCost' => 'required|numeric|min:0',
            'details.*.receivedQuantity' => 'required|integer|min:0|lte:details.*.quantity',
        ]);

        $totalCost = collect($request->details)->sum(function ($detail) {
            return $detail['quantity'] * $detail['unitCost'];
        });

        $supplierOrder->update([
            'supplierID' => $request->supplierID,
            'orderDate' => $request->orderDate,
            'expectedDeliveryDate' => $request->expectedDeliveryDate,
            'totalCost' => $totalCost,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        // Sync order details (no stock/price updates here, only on receive)
        $existingDetailIds = $supplierOrder->details->pluck('supplierOrderDetailID')->toArray();
        $submittedDetailIds = collect($request->details)->pluck('supplierOrderDetailID')->filter()->toArray();

        SupplierOrderDetail::where('supplierOrderID', $supplierOrder->supplierOrderID)
            ->whereNotIn('supplierOrderDetailID', $submittedDetailIds)
            ->delete();

        foreach ($request->details as $detail) {
            SupplierOrderDetail::updateOrCreate(
                ['supplierOrderDetailID' => $detail['supplierOrderDetailID'] ?? null],
                [
                    'supplierOrderID' => $supplierOrder->supplierOrderID,
                    'productID' => $detail['productID'],
                    'quantity' => $detail['quantity'],
                    'unitCost' => $detail['unitCost'],
                    'receivedQuantity' => $detail['receivedQuantity'],
                ]
            );
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
