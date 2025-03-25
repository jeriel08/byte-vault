<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('brand', 'category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::where('brandStatus', 'Active')->get();
        $categories = Category::where('categoryStatus', 'Active')->get();
        return view('products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string',
            'brandID' => 'required|exists:brands,brandID',
            'categoryID' => 'required|exists:categories,categoryID',
            'productStatus' => 'required|in:Active,Inactive',
        ]);

        Product::create([
            'productName' => $request->productName,
            'productDescription' => $request->productDescription,
            'brandID' => $request->brandID,
            'categoryID' => $request->categoryID,
            'price' => 0,  // Default value
            'stockQuantity' => 0,  // Default value
            'productStatus' => $request->productStatus,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('brandStatus', 'Active')->get();
        $categories = Category::where('categoryStatus', 'Active')->get();
        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, $productID)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string',
            'brandID' => 'required|exists:brands,brandID',
            'categoryID' => 'required|exists:categories,categoryID',
            'productStatus' => 'required|in:Active,Inactive',
        ]);

        $product = Product::findOrFail($productID);
        $product->update([
            'productName' => $request->productName,
            'productDescription' => $request->productDescription,
            'brandID' => $request->brandID,
            'categoryID' => $request->categoryID,
            'productStatus' => $request->productStatus,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        //
    }
}
