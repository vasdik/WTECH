<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
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


Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/step-1', [CheckoutController::class, 'step1'])->name('step1');
    Route::post('/step-1', [CheckoutController::class, 'storeStep1'])->name('step1.store');

    Route::get('/step-2', [CheckoutController::class, 'step2'])->name('step2');
    Route::post('/step-2', [CheckoutController::class, 'storeStep2'])->name('step2.store');

    Route::get('/step-3', [CheckoutController::class, 'step3'])->name('step3');
    Route::post('/step-3', [CheckoutController::class, 'storeStep3'])->name('step3.store');

    Route::get('/step-4', [CheckoutController::class, 'step4'])->name('step4');
    Route::post('/step-4', [CheckoutController::class, 'storeStep4'])->name('step4.store');

    Route::get('/step-5', [CheckoutController::class, 'step5'])->name('step5');
    Route::post('/complete', [CheckoutController::class, 'complete'])->name('complete');

    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');

        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::delete(
            'products/{product}/images/{image}',
            [AdminProductController::class, 'destroyImage']
        )->name('products.images.destroy');
});

require __DIR__.'/auth.php';