<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('welcome');
}); */


Route::get('/', [HomeController::class, 'index'])
    ->name('home');


Route::view('/saved', 'cart.saved')
    ->name('saved');


Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/{product}/increment', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/{product}/decrement', [CartController::class, 'decrement'])->name('cart.decrement');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');


Route::get('/search', [SearchController::class, 'index'])
    ->name('search.index');


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
