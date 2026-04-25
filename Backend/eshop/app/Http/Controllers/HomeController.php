<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $carouselProducts = Product::query()
            ->with(['images', 'color', 'weight', 'diameter'])
            ->where('is_active', true)
            ->latest('id')
            ->take(12)
            ->get();

        return view('shop.home', [
            'carouselProducts' => $carouselProducts,
        ]);
    }
}