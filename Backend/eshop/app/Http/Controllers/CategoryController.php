<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
public function show(Request $request, string $category, ?string $subcategory = null)
    {
        $categoryMap = $this->categoryMap();

        abort_unless(array_key_exists($category, $categoryMap), 404);

        $currentCategory = $categoryMap[$category];
        $currentSubcategory = $subcategory
            ? collect($currentCategory['subcategories'])->firstWhere('slug', $subcategory)
            : null;

        if ($subcategory && ! $currentSubcategory) {
            abort(404);
        }

        $products = collect($this->fakeProducts())
            ->filter(function (array $product) use ($category, $subcategory) {
                if ($product['category_slug'] !== $category) {
                    return false;
                }

                if ($subcategory && $product['subcategory_slug'] !== $subcategory) {
                    return false;
                }

                return true;
            })
            ->values();

        $sort = request('sort');

        if ($sort === 'name_asc') {
            $products = $products->sortBy('name')->values();
        } elseif ($sort === 'name_desc') {
            $products = $products->sortByDesc('name')->values();
        } elseif ($sort === 'price_asc') {
            $products = $products->sortBy('price')->values();
        } elseif ($sort === 'price_desc') {
            $products = $products->sortByDesc('price')->values();
        }

        $pageTitle = $currentSubcategory['name'] ?? $currentCategory['name'];

        return view('shop.partials.shop.category', [
            'category' => $currentCategory,
            'subcategory' => $currentSubcategory,
            'products' => $products,
            'pageTitle' => $pageTitle,
            'sort' => $sort,
            'carouselProducts' => collect($this->fakeProducts())->take(4),
        ]);
    }

    private function categoryMap(): array
    {
        return [
            'filaments' => [
                'name' => 'Filaments',
                'slug' => 'filaments',
                'subcategories' => [
                    ['name' => 'PLA', 'slug' => 'pla'],
                    ['name' => 'PETG', 'slug' => 'petg'],
                    ['name' => 'ASA', 'slug' => 'asa'],
                    ['name' => 'ABS', 'slug' => 'abs'],
                    ['name' => 'NYLON', 'slug' => 'nylon'],
                    ['name' => 'TPU', 'slug' => 'tpu'],
                ],
            ],
            'resins' => [
                'name' => 'Resins',
                'slug' => 'resins',
                'subcategories' => [
                    ['name' => 'Standard', 'slug' => 'standard'],
                    ['name' => 'Tough', 'slug' => 'tough'],
                ],
            ],
            'printers' => [
                'name' => 'Printers',
                'slug' => 'printers',
                'subcategories' => [],
            ],
            'accessories' => [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'subcategories' => [],
            ],
            'tools' => [
                'name' => 'Tools',
                'slug' => 'tools',
                'subcategories' => [],
            ],
            'brands' => [
                'name' => 'Brands',
                'slug' => 'brands',
                'subcategories' => [],
            ],
            'sale' => [
                'name' => 'Sale',
                'slug' => 'sale',
                'subcategories' => [],
            ],
        ];
    }

    private function fakeProducts(): array
    {
        return [
            [
                'slug' => 'polyterra-pla-charcoal-black',
                'category_slug' => 'filaments',
                'subcategory_slug' => 'pla',
                'brand' => 'Polymaker',
                'name' => 'PolyTerra PLA Charcoal Black, 1,75 mm / 1000 g',
                'price' => 15.99,
                'rating' => 4.5,
                'image' => 'images/products/Polyterra_PLA/polyterra_PLA_Black_1_.512x512.avif',
            ],
            [
                'slug' => 'elegoo-pla-magic-red-blue',
                'category_slug' => 'filaments',
                'subcategory_slug' => 'pla',
                'brand' => 'Elegoo',
                'name' => 'PLA Magic Red&Blue, 1,75 mm / 1000 g',
                'price' => 38.99,
                'rating' => 4.0,
                'image' => 'images/products/Elegoo_PLA_Magic/elegoo_PLA_Black_Purple_1_512x512.avif',
            ],
            [
                'slug' => 'esun-pla-black',
                'category_slug' => 'filaments',
                'subcategory_slug' => 'pla',
                'brand' => 'eSUN',
                'name' => 'PLA Black, 1,75 mm / 1000 g',
                'price' => 15.99,
                'rating' => 1.7,
                'image' => 'images/products/eSun_PLA/esun_PLA_Black_1_.512x512.avif',
            ],
            [
                'slug' => 'bambulab-pla-matte-white',
                'category_slug' => 'filaments',
                'subcategory_slug' => 'pla',
                'brand' => 'Bambulab',
                'name' => 'PLA Matte Jade White, 1,75 mm / 1000 g',
                'price' => 27.99,
                'rating' => 4.5,
                'image' => null,
            ],
            [
                'slug' => 'prusament-petg-clear',
                'category_slug' => 'filaments',
                'subcategory_slug' => 'petg',
                'brand' => 'Prusament',
                'name' => 'PETG Clear, 1,75 mm / 1000 g',
                'price' => 24.99,
                'rating' => 4.4,
                'image' => null,
            ],
            [
                'slug' => 'resin-standard-grey',
                'category_slug' => 'resins',
                'subcategory_slug' => 'standard',
                'brand' => 'Elegoo',
                'name' => 'Standard Resin Grey, 1000 g',
                'price' => 21.99,
                'rating' => 4.2,
                'image' => null,
            ],
            [
                'slug' => 'bambu-a1-mini',
                'category_slug' => 'printers',
                'subcategory_slug' => null,
                'brand' => 'Bambulab',
                'name' => 'A1 Mini Combo',
                'price' => 489.00,
                'rating' => 4.8,
                'image' => null,
            ],
            [
                'slug' => 'flush-cutters',
                'category_slug' => 'tools',
                'subcategory_slug' => null,
                'brand' => 'Generic',
                'name' => 'Flush Cutters',
                'price' => 6.99,
                'rating' => 4.1,
                'image' => null,
            ],
        ];
    }
}