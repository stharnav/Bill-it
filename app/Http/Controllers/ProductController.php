<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {   
        $products = Product::with('category')->get();
        $currentPage = 'products';
        return view('products.product', compact('products', 'currentPage'));
    }

    public function fetchCategories()
    {
        $categories =  Category::select('id', 'name')->get();
        $currentPage = 'add-product';
        return view('products.add-product', compact('categories', 'currentPage'));
    }

    public function store(Request $request)
    {   
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:category,id',
        ]);

        $product = Product::create($validated);

         return redirect()
        ->route('products.add-product')
        ->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()
            ->route('products.product')
            ->with('success', 'Product updated successfully!');
    }

}
