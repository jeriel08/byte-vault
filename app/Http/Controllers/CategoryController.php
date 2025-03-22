<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::whereNull('parentCategoryID')->get();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
            'parentCategoryID' => 'nullable|exists:categories,categoryID',
            'categoryStatus' => 'required|in:Active,Inactive',
        ]);

        Category::create([
            'categoryName' => $request->categoryName,
            'categoryDescription' => $request->categoryDescription,
            'parentCategoryID' => $request->parentCategoryID,
            'categoryStatus' => $request->categoryStatus,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoryID)
    {
        $category = Category::find($categoryID);
        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'Category not found.');
        }
        $categories = Category::whereNull('parentCategoryID')->where('categoryID', '!=', $categoryID)->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $categoryID)
    {
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
            'parentCategoryID' => 'nullable|exists:categories,categoryID',
            'categoryStatus' => 'required|in:Active,Inactive',
        ]);

        $category = Category::findOrFail($categoryID);
        $category->update([
            'categoryName' => $request->categoryName,
            'categoryDescription' => $request->categoryDescription,
            'parentCategoryID' => $request->parentCategoryID,
            'categoryStatus' => $request->categoryStatus,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
