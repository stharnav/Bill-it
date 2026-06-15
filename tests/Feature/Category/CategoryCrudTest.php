<?php

use App\Models\Category;
use App\Models\User;
use App\Models\Log;

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => 0, 'status' => 1]);
    $this->actingAs($this->admin);
});

test('category index page loads', function () {
    Category::factory()->create(['name' => 'Test Category']);

    $response = $this->get('/category');

    $response->assertStatus(200);
    $response->assertSee('Test Category');
});

test('admin can create a category', function () {
    $response = $this->post('/category/store', [
        'name' => 'Electronics',
        'description' => 'Electronic items',
    ]);

    $response->assertRedirect();
    expect(Category::where('name', 'Electronics')->exists())->toBeTrue();

    // Assert log was created
    expect(Log::where('description', 'created category: Electronics')->exists())->toBeTrue();
});

test('admin can update a category', function () {
    $category = Category::factory()->create(['name' => 'Old Name']);

    $response = $this->put("/category/{$category->id}", [
        'name' => 'Updated Name',
        'description' => 'Updated description',
    ]);

    $response->assertRedirect();
    expect($category->fresh()->name)->toBe('Updated Name');
});

test('admin can delete a category', function () {
    $category = Category::factory()->create(['name' => 'To Delete']);

    $response = $this->delete("/category/{$category->id}");

    $response->assertRedirect();
    expect(Category::find($category->id))->toBeNull();
});
