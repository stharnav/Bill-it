<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => 0, 'status' => 1]);
    $this->actingAs($this->admin);
});

test('product index page loads', function () {
    $category = Category::factory()->create();
    Product::factory()->create(['name' => 'Test Product', 'category_id' => $category->id]);

    $response = $this->get('/products');

    $response->assertStatus(200);
    $response->assertSee('Test Product');
});

test('admin can create a product', function () {
    $category = Category::factory()->create();

    $response = $this->post('/product/store', [
        'name' => 'New Product',
        'price' => 99.99,
        'stock' => 10,
        'category_id' => $category->id,
        'description' => 'A test product',
    ]);

    $response->assertRedirect();
    expect(Product::where('name', 'New Product')->exists())->toBeTrue();
    expect(Product::where('name', 'New Product')->first()->stock)->toBe(10);
});

test('admin can update a product', function () {
    $category = Category::factory()->create();
    $category2 = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 5]);

    $response = $this->put("/product/{$product->id}", [
        'name' => 'Updated Product',
        'price' => 49.99,
        'stock' => 20,
        'category_id' => $category2->id,
    ]);

    $response->assertRedirect();
    $product->fresh();
    expect($product->fresh()->name)->toBe('Updated Product');
    expect($product->fresh()->stock)->toBe(20);
});

test('admin can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->delete("/product/{$product->id}");

    $response->assertRedirect();
    expect(Product::find($product->id))->toBeNull();
});
