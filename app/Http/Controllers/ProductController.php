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
        $currentPage = 'product';
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
}
