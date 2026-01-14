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

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit-category', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        $currentPage = 'category';

        return redirect()->route('categories.category')
        ->with('currentPage', 'categories')
                         ->with('success', 'Category updated successfully!');
    }

}
