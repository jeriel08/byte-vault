<?php

namespace App\Http\Controllers;

use App\Models\ReturnToSupplier;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\StockOut;
use App\Models\StockOutDetail;
use Illuminate\Http\Request;

class ReturnToSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $returns = ReturnToSupplier::with('supplier', 'stockOut', 'creator')->get();
        return view('supplier_returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $orders = SupplierOrder::with(['supplier', 'details.product'])
            ->whereNotNull('receivedDate')
            ->whereNull('cancelledDate')
            ->get();
        $products = Product::where('productStatus', 'Active')->get();
        $order = $request->has('order') ? SupplierOrder::with('details.product')->findOrFail($request->order) : null;

        if ($order && !$order->receivedDate) {
            return redirect()->route('supplier_returns.index')->with('error', 'Selected order has not been received.');
        }

        // Ensure relationships are included in JSON
        $orders->each(function ($order) {
            $order->setRelation('details', $order->details->load('product'));
        });

        return view('supplier_returns.create', compact('orders', 'products', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplierOrderID' => 'required|exists:supplier_orders,supplierOrderID',
            'returnDate' => 'required|date',
            'returnSupplierReason' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.productID' => 'required|exists:products,productID',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        $order = SupplierOrder::findOrFail($request->supplierOrderID);
        $totalQuantity = collect($request->details)->sum('quantity');

        $return = ReturnToSupplier::create([
            'supplierID' => $order->supplierID,
            'supplierOrderID' => $request->supplierOrderID,
            'returnDate' => $request->returnDate,
            'returnSupplierReason' => $request->returnSupplierReason,
            'status' => ReturnToSupplier::STATUS_PENDING,
            'created_by' => auth()->id(),
        ]);

        $stockOut = StockOut::create([
            'reasonType' => 'return_to_supplier',
            'referenceID' => $return->returnSupplierID,
            'referenceTable' => 'return_to_suppliers',
            'totalQuantity' => $totalQuantity,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->details as $detail) {
            StockOutDetail::create([
                'stockOutID' => $stockOut->stockOutID,
                'productID' => $detail['productID'],
                'quantity' => $detail['quantity'],
            ]);
        }

        return redirect()->route('supplier_returns.index')->with('success', 'Return recorded as Pending.');
    }

    public function complete(Request $request, $returnSupplierID)
    {
        $return = ReturnToSupplier::findOrFail($returnSupplierID);
        if ($return->status !== 'Pending') {
            return redirect()->route('supplier_returns.index')->with('error', 'Return cannot be completed from its current status.');
        }
        $return->update([
            'status' => 'Completed',
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);
        foreach ($return->stockOut->details as $detail) {
            Product::find($detail->productID)->decrement('stockQuantity', $detail->quantity);
        }
        return redirect()->route('supplier_returns.index')->with('success', 'Return marked as Completed, stock updated.');
    }

    public function reject(Request $request, $returnSupplierID)
    {
        $return = ReturnToSupplier::findOrFail($returnSupplierID);
        if ($return->status !== 'Pending') {
            return redirect()->route('supplier_returns.index')->with('error', 'Return cannot be rejected from its current status.');
        }
        $request->validate(['rejectionReason' => 'required|string|max:255']);
        $return->update([
            'status' => 'Rejected',
            'rejectionReason' => $request->rejectionReason,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);
        return redirect()->route('supplier_returns.index')->with('success', 'Return marked as Rejected.');
    }

    /**
     * Display the specified resource.
     */
    public function show($returnSupplierID)
    {
        $return = ReturnToSupplier::with(['creator', 'supplierOrder.supplier', 'stockOut.details.product'])
            ->findOrFail($returnSupplierID);
        return view('supplier_returns.show', compact('return'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnToSupplier $returnToSupplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnToSupplier $returnToSupplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnToSupplier $returnToSupplier)
    {
        //
    }
}
