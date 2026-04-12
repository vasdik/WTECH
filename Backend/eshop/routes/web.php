<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('welcome');
}); */

Route::view('/', 'shop.home')->name('home');


Route::view('/saved', 'cart.saved')->name('saved');

Route::view('/cart', 'cart.cart')->name('cart');


Route::get('/categories/{category}/{subcategory?}', [CategoryController::class, 'show'])
    ->name('categories.show');


Route::get('/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
