<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'category.parent',
            'images',
            'variants.color',
            'variants.weight',
            'variants.diameter',
            'variants.images',
            'defaultVariant.color',
            'defaultVariant.weight',
            'defaultVariant.diameter',
            'filamentDetail.filamentType',
        ]);

        $variantSlug = $request->string('variant')->toString();

        $selectedVariant = null;

        if ($variantSlug !== '') {
            $selectedVariant = $product->variants->firstWhere('slug', $variantSlug);
        }

        $selectedVariant ??= $product->defaultVariant;
        $selectedVariant ??= $product->variants->first();

        $galleryImages = collect();

        if ($selectedVariant) {
            $galleryImages = $product->images
                ->where('product_variant_id', $selectedVariant->id)
                ->values();
        }

        if ($galleryImages->isEmpty()) {
            $galleryImages = $product->images
                ->whereNull('product_variant_id')
                ->values();
        }

        if ($galleryImages->isEmpty()) {
            $galleryImages = $product->images->values();
        }

        $primaryImage = $galleryImages->firstWhere('is_primary', true) ?? $galleryImages->first();

        $colorOptions = $product->variants
            ->filter(fn ($variant) => $variant->color)
            ->groupBy('color_id')
            ->map(fn ($variants) => $variants->first())
            ->values();

        $variantOptions = $product->variants->values();

        $displayPrice = (float) ($selectedVariant?->price_gross ?? $product->price_gross);
        $stockQty = (int) ($selectedVariant?->stock_qty ?? $product->stock_qty);
        $taxRate = (float) $product->tax_rate;
        $priceNet = round($displayPrice / (1 + ($taxRate / 100)), 2);

        $relatedProducts = Product::query()
            ->with([
                'images',
                'defaultVariant.color',
                'defaultVariant.weight',
                'defaultVariant.diameter',
            ])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest('rating_avg')
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $fallbackProducts = Product::query()
                ->with([
                    'images',
                    'defaultVariant.color',
                    'defaultVariant.weight',
                    'defaultVariant.diameter',
                ])
                ->where('is_active', true)
                ->whereKeyNot($relatedProducts->pluck('id')->push($product->id))
                ->latest('rating_avg')
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($fallbackProducts);
        }

        return view('shop.partials.shop.product-show', [
            'product' => $product,
            'selectedVariant' => $selectedVariant,
            'galleryImages' => $galleryImages,
            'primaryImage' => $primaryImage,
            'colorOptions' => $colorOptions,
            'variantOptions' => $variantOptions,
            'displayPrice' => $displayPrice,
            'priceNet' => $priceNet,
            'stockQty' => $stockQty,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}