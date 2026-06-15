<?php

use App\Models\Product;
use App\Models\Category;

test('product belongs to a category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category)->toBeInstanceOf(Category::class);
    expect($product->category->id)->toBe($category->id);
});

test('product has stock attribute with default value', function () {
    $product = Product::factory()->create(['stock' => 0]);

    expect($product->stock)->toBe(0);
});
