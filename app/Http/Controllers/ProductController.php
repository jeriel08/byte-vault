<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('brand', 'category')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $brands = Brand::where('brandStatus', 'Active')->get();
        $categories = Category::where('categoryStatus', 'Active')->get();
        return view('products.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string',
            'brandID' => 'required|exists:brands,brandID',
            'categoryID' => 'required|exists:categories,categoryID',
            'price' => 'required|numeric|min:0',
            'stockQuantity' => 'required|integer|min:0',
            'productStatus' => 'required|in:Active,Inactive',
        ]);

        Product::create([
            'productName' => $request->productName,
            'productDescription' => $request->productDescription,
            'brandID' => $request->brandID,
            'categoryID' => $request->categoryID,
            'price' => $request->price,
            'stockQuantity' => $request->stockQuantity,
            'productStatus' => $request->productStatus,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::where('brandStatus', 'Active')->get();
        $categories = Category::where('categoryStatus', 'Active')->get();
        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $productID)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string',
            'brandID' => 'required|exists:brands,brandID',
            'categoryID' => 'required|exists:categories,categoryID',
            'price' => 'required|numeric|min:0',
            'stockQuantity' => 'required|integer|min:0',
            'productStatus' => 'required|in:Active,Inactive',
        ]);

        $product = Product::findOrFail($productID);
        $product->update([
            'productName' => $request->productName,
            'productDescription' => $request->productDescription,
            'brandID' => $request->brandID,
            'categoryID' => $request->categoryID,
            'price' => $request->price,
            'stockQuantity' => $request->stockQuantity,
            'productStatus' => $request->productStatus,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
