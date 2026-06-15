<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\Sales;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => 0, 'status' => 1]);
    $this->actingAs($this->admin);
});

test('new sale page loads', function () {
    $response = $this->get('/new-sales');

    $response->assertStatus(200);
});

test('admin can create a sale with products', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 100.00,
        'stock' => 10,
    ]);

    $response = $this->post('/sales/store', [
        'mode_of_payment' => 1,
        'products' => [
            ['id' => $product->id, 'qty' => 2],
        ],
        'discount' => 0,
        'tax' => 0,
        'customer_name' => 'John Doe',
        'is_refund' => 0,
    ]);

    $response->assertRedirect();

    // Sale record created
    $sale = Sales::where('customer_name', 'John Doe')->first();
    expect($sale)->not->toBeNull();
    expect($sale->bill_no)->toMatch('/^INV-/');

    // Stock decremented
    expect($product->fresh()->stock)->toBe(8);
});

test('sale without products is rejected', function () {
    $response = $this->post('/sales/store', [
        'mode_of_payment' => 1,
        'products' => [],
        'is_refund' => 0,
    ]);

    $response->assertSessionHasErrors('products');
});
