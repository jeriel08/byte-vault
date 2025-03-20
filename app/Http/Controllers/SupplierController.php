<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    // Store a new supplier
    public function store(Request $request)
    {
        $request->validate([
            'supplierName' => 'required|string|max:255',
            'supplierAddress' => 'nullable|string',
            'supplierPhoneNumber' => 'nullable|string|max:20',
            'supplierProfileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image upload
            'supplierStatus' => 'required|in:Active,Inactive',
        ]);

        // Handle image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('supplierProfileImage')) {
            $imagePath = $request->file('supplierProfileImage')->store('supplier_images', 'public');
        }

        // Create the supplier
        $supplier = Supplier::create([
            'supplierName' => $request->supplierName,
            'supplierAddress' => $request->supplierAddress,
            'supplierPhoneNumber' => $request->supplierPhoneNumber,
            'supplierProfileImage' => $imagePath,
            'supplierStatus' => $request->supplierStatus ?? 'Active',
            'created_by' => auth()->user(), // Assuming you're using authentication
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
        $supplier = Supplier::findOrFail($supplierID);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $supplierID)
    {
        // Validate the request data
        $request->validate([
            'supplierName' => 'required|string|max:255',
            'supplierAddress' => 'nullable|string',
            'supplierPhoneNumber' => 'nullable|string|max:20',
            'supplierProfileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'supplierStatus' => 'nullable|string', // Add validation for supplierStatus
        ]);

        // Find the supplier by ID
        $supplier = Supplier::findOrFail($supplierID);

        // // Handle image upload (if provided)
        // if ($request->hasFile('supplierProfileImage')) {
        //     // Delete the old image if it exists
        //     if ($supplier->supplierProfileImage) {
        //         Storage::delete('public/' . $supplier->supplierProfileImage);
        //     }
        //     // Store the new image
        //     $imagePath = $request->file('supplierProfileImage')->store('supplier_images', 'public');
        //     $supplier->supplierProfileImage = $imagePath;
        // }

        // Update the supplier data
        $supplier->update([
            'supplierName' => $request->supplierName,
            'supplierAddress' => $request->supplierAddress,
            'supplierPhoneNumber' => $request->supplierPhoneNumber,
            'supplierStatus' => $request->supplierStatus, // Add supplierStatus
        ]);

        // Redirect with a success message
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
