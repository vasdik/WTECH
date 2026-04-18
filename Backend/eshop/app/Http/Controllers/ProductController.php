<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'category.parent',
            'family.products.images',
            'family.products.color',
            'family.products.weight',
            'family.products.diameter',
            'images',
            'color',
            'weight',
            'diameter',
            'filamentDetail.filamentType',
        ]);

        $familyProducts = $product->family
            ? $product->family->products->where('is_active', true)->values()
            : collect([$product]);

        $galleryImages = $product->images->values();
        $primaryImage = $galleryImages->firstWhere('is_primary', true) ?? $galleryImages->first();

        $colorProducts = $familyProducts
            ->filter(fn ($item) => $item->color)
            ->groupBy('color_id')
            ->map(fn (Collection $group) => $group->first())
            ->values();

        $variantProducts = $familyProducts
            ->filter(fn ($item) => filled($item->variant_label))
            ->values();

        $displayPrice = (float) $product->price_gross;
        $taxRate = (float) $product->tax_rate;
        $priceNet = round($displayPrice / (1 + ($taxRate / 100)), 2);
        $stockQty = (int) $product->stock_qty;

        $relatedProducts = Product::query()
            ->with(['images', 'color', 'weight', 'diameter'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest('rating_avg')
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $fallbackProducts = Product::query()
                ->with(['images', 'color', 'weight', 'diameter'])
                ->where('is_active', true)
                ->whereKeyNot($relatedProducts->pluck('id')->push($product->id))
                ->latest('rating_avg')
                ->take(12)
                ->get();

            $relatedProducts = $relatedProducts->concat($fallbackProducts);
        }

        return view('shop.partials.shop.product-show', [
            'product' => $product,
            'galleryImages' => $galleryImages,
            'primaryImage' => $primaryImage,
            'colorProducts' => $colorProducts,
            'variantProducts' => $variantProducts,
            'displayPrice' => $displayPrice,
            'priceNet' => $priceNet,
            'stockQty' => $stockQty,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}