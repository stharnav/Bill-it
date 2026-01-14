<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{   
    public function index()
    {
        $categories = Category::all();
        $currentPage = 'category';
        return view('categories.category', compact('categories', 'currentPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

         return redirect()
        ->route('categories.add-category')
        ->with('success', 'Category created successfully!');
    }
}
