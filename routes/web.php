<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    return view('welcome', ['currentPage' => 'home']);
});

Route::get('/sales', function () {
    return view('sales.sale', ['currentPage' => 'sales']);
});

Route::get('/products', [ProductController::class, 'index'])->name('products.product');
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
Route::get('/add-product', [ProductController::class, 'fetchCategories'])->name('products.add-product');
Route::get('/product/{id}/edit', [ProductController::class, 'edit'])
    ->name('product.edit');

Route::put('/product/{id}', [ProductController::class, 'update'])
    ->name('product.update');



Route::get('/category', [CategoryController::class, 'index'])
    ->name('categories.category');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/add-category', function () {
    return view('categories.add-category', ['currentPage' => 'add-category']);
})->name('categories.add-category');
Route::get('/category/{id}/editCategory', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');


Route::get('/about-me', function () {
    return view('about.about-me', ['currentPage' => 'about-me']);
});

Route::get('/about-company', [CompanyController::class, 'index'])->name('company.about-company');
Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
Route::post('/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');

Route::get('/login', function () {
    return view('auth.login');
});