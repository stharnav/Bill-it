<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Log;

class ProductController extends Controller
{
    /**
     * Auto-generate a unique SKU number.
     */
    private function generateSku(): string
    {
        $nextId = (Product::max('id') ?? 0) + 1;
        return 'SKU-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

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
        $suggestedSku = $this->generateSku();
        return view('products.add-product', compact('categories', 'currentPage', 'suggestedSku'));
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'nullable|integer|min:0',
                'sku_number' => 'nullable|string|max:50|unique:product,sku_number',
                'category_id' => 'required|integer|exists:category,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        // Auto-generate SKU if not provided
        if (empty($validated['sku_number'])) {
            $validated['sku_number'] = $this->generateSku();
        }

        $product = Product::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'description' => 'created product: ' . $product->name,
        ]);

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
            'stock' => 'nullable|integer|min:0',
            'sku_number' => 'nullable|string|max:50|unique:product,sku_number,'.$id,
            'category_id' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        // Auto-generate SKU only if empty and product has no existing SKU
        if (empty($data['sku_number']) && empty($product->sku_number)) {
            $data['sku_number'] = $this->generateSku();
        }

        $product->update($data);

        Log::create([
            'user_id' => auth()->id(),
            'description' => 'updated product: ' . $product->name,
        ]);

        return redirect()
            ->route('products.product')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->name;
        $product->delete();

        Log::create([
            'user_id' => auth()->id(),
            'description' => 'deleted product: ' . $productName,
        ]);

        return redirect()
            ->route('products.product')
            ->with('success', 'Product deleted successfully!');
    }
}
