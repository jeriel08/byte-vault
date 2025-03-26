<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\StockOut;
use App\Models\StockOutDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adjustments = Adjustment::with('stockOut.details.product')->get();
        Log::info('Adjustments loaded', ['first_stockOut' => $adjustments->first()->stockOut ? $adjustments->first()->stockOut->toArray() : null]);
        return view('adjustments.index', compact('adjustments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::where('productStatus', 'active')->get();
        if ($products->isEmpty()) {
            $products = collect(); // Fallback to empty collection if no data
        }
        return view('adjustments.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'adjustmentDate' => 'required|date',
            'adjustmentReason' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*.productID' => 'required|exists:products,productID',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'adjustmentDate' => $request->adjustmentDate,
                'adjustmentReason' => $request->adjustmentReason,
                'created_by' => Auth::user()->employeeID,
            ]);

            $totalQuantity = array_sum(array_column($request->products, 'quantity'));
            $stockOut = StockOut::create([
                'reasonType' => 'adjustment',
                'referenceID' => $adjustment->adjustmentID,
                'referenceTable' => 'adjustments',
                'totalQuantity' => $totalQuantity,
                'created_by' => Auth::user()->employeeID,
            ]);
            Log::info('StockOut created', ['id' => $stockOut->stockOutID, 'referenceID' => $stockOut->referenceID]);

            foreach ($request->products as $item) {
                $detail = StockOutDetail::create([
                    'stockOutID' => $stockOut->stockOutID,
                    'productID' => $item['productID'],
                    'quantity' => $item['quantity'],
                ]);

                Log::info('StockOutDetail created', ['id' => $detail->stockOutDetailID]);

                $product = Product::find($item['productID']);
                $newStock = $product->stockQuantity - $item['quantity'];
                if ($newStock < 0) {
                    throw new \Exception("Stock for {$product->name} cannot go below 0.");
                }
                $product->update(['stockQuantity' => $newStock]);
            }
        });

        return redirect()->route('adjustments.index')->with('success', 'Adjustment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adjustment $adjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adjustment $adjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adjustment $adjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adjustment $adjustment)
    {
        //
    }
}
