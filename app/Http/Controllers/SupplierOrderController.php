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
        $query = SupplierOrder::with('supplier')->latest(); // Default sort by recent

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['Pending', 'Received', 'Cancelled'])) {
            $query->where('status', $request->status);
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplierID', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('orderDate', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('orderDate', '<=', $request->date_to);
        }

        // Sort by
        if ($request->has('sort_by')) {
            if ($request->sort_by === 'date_asc') {
                $query->oldest('orderDate');
            } elseif ($request->sort_by === 'date_desc') {
                $query->latest('orderDate');
            }
        }

        $supplierOrders = $query->get();
        $suppliers = Supplier::where('supplierStatus', 'Active')->get(); // For the supplier filter dropdown

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

        $request->validate([
            'supplierID' => 'required|exists:suppliers,supplierID',
            'orderDate' => 'required|date',
            'expectedDeliveryDate' => 'nullable|date|after_or_equal:orderDate',
            'status' => 'required|in:Pending,Received,Cancelled',
            'details' => 'required|array',
            'details.*.supplierOrderDetailID' => 'sometimes|exists:supplier_order_details,supplierOrderDetailID',
            'details.*.productID' => 'required|exists:products,productID',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unitCost' => 'required|numeric|min:0',
            'details.*.receivedQuantity' => 'required|integer|min:0',
            'details.*.status' => 'required|in:Pending,Received,Cancelled',
        ]);

        // Update supplier order main details
        $supplierOrder->update([
            'supplierID' => $request->supplierID,
            'orderDate' => $request->orderDate,
            'expectedDeliveryDate' => $request->expectedDeliveryDate,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        // Calculate total cost and update details
        $totalCost = 0;
        $allDetailsReceived = true;

        foreach ($request->details as $index => $detailData) {
            $subtotal = $detailData['quantity'] * $detailData['unitCost'];
            $totalCost += $subtotal;

            $detail = SupplierOrderDetail::findOrFail($detailData['supplierOrderDetailID']);
            $oldStatus = $detail->status;

            // Validate receivedQuantity server-side
            if ($detailData['receivedQuantity'] > $detailData['quantity']) {
                return back()->withErrors(['details.' . $index . '.receivedQuantity' => 'Received Quantity cannot exceed Ordered Quantity.']);
            }

            // If order status is Received, set all details to Received and receivedQuantity to quantity
            if ($request->status === 'Received') {
                $detailData['status'] = 'Received';
                $detailData['receivedQuantity'] = $detailData['quantity'];
            }
            // If order status is Cancelled, set all details to Cancelled and reset receivedQuantity
            elseif ($request->status === 'Cancelled') {
                $detailData['status'] = 'Cancelled';
                $detailData['receivedQuantity'] = 0;
            }
            // If receivedQuantity equals quantity, set status to Received
            elseif ($detailData['receivedQuantity'] == $detailData['quantity']) {
                $detailData['status'] = 'Received';
            }

            $detail->update([
                'quantity' => $detailData['quantity'],
                'unitCost' => $detailData['unitCost'],
                'receivedQuantity' => $detailData['receivedQuantity'],
                'status' => $detailData['status'],
            ]);

            // Update product if status changes to Received
            if ($detailData['status'] === 'Received' && $oldStatus !== 'Received') {
                $product = Product::findOrFail($detail->productID);
                $product->update([
                    'stockQuantity' => $product->stockQuantity + $detailData['receivedQuantity'],
                    'price' => $detailData['unitCost'],
                    'updated_by' => auth()->id(),
                ]);
            }

            // Check if all details are Received
            if ($detailData['status'] !== 'Received') {
                $allDetailsReceived = false;
            }
        }

        // If all details are Received and order status isn’t already Received, update it
        if ($allDetailsReceived && $supplierOrder->status !== 'Received') {
            $supplierOrder->update(['status' => 'Received']);
        }

        $supplierOrder->update(['totalCost' => $totalCost]);

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
