<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $adjustments = Adjustment::all();
        return view('adjustments.index', compact('adjustments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('adjustments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'adjustmentDate' => 'required|date',
            'adjustmentReason' => 'required|string|max:255',
        ]);

        Adjustment::create([
            'adjustmentDate' => $request->adjustmentDate,
            'adjustmentReason' => $request->adjustmentReason,
            'created_by' => auth()->id(),
        ]);

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
