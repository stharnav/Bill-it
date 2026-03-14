<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReportController;

Route::middleware(['auth'])->group(function(){

    Route::get('/', [UserController::class, 'welcome'])->name('home');

    Route::get('/sales', [SalesController::class, 'index'] )->name('sales.sale');
    Route::get('/sales/search', [SalesController::class, 'search'])->name('sales.search');
    Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/new-sales', function () {
        return view('sales.new-sales', ['currentPage' => 'new-sales']);
    })->name('sales.new-sales');
    Route::get('/sales/refund/{id}', [SalesController::class, 'refund'])->name('sales.refund');
    Route::post('/sales/new-refund', [SalesController::class, 'processRefund'])->name('sales.processRefund');

    Route::get('/bill/{id}', [SalesController::class, 'bill'])->name('sales.bill');

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

    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('reports.sales-report');
    Route::get('/sales-report/search', [SalesReportController::class, 'search'])->name('reports.sales-report.search');

    Route::get('/about-me', function () {
        return view('about.about-me', ['currentPage' => 'about-me']);
    });
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [UserController::class, 'updatePassword'])->name('password.update');
    Route::get('/about-company', [CompanyController::class, 'index'])->name('company.about-company');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    Route::post('/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');
    Route::get('/about-users', [UserController::class, 'index'])->name('user.user');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.add-user');

});


Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login-check', [UserController::class, 'login'])->name('login.check');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');