<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\enum\Color;
use App\Models\enum\Diameter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $category, ?string $subcategory = null)
    {
        $rootCategory = Category::query()
            ->whereNull('parent_id')
            ->where('slug', $category)
            ->where('is_active', true)
            ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])
            ->firstOrFail();

        $activeCategory = $rootCategory;

        if ($subcategory) {
            $activeCategory = Category::query()
                ->where('parent_id', $rootCategory->id)
                ->where('slug', $subcategory)
                ->where('is_active', true)
                ->firstOrFail();
        }

        $categoryIds = $subcategory
            ? [$activeCategory->id]
            : $this->resolveCategoryIdsForListing($rootCategory);

        $productsQuery = Product::query()
            ->with([
                'images',
                'color',
                'weight',
                'diameter',
                'filamentDetail.filamentType',
                'category',
                'family',
            ])
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds);

        $productsQuery
            ->when($request->filled('brand'), function (Builder $query) use ($request) {
                $query->where('brand', $request->string('brand')->toString());
            })
            ->when($request->filled('color'), function (Builder $query) use ($request) {
                $query->whereHas('color', function (Builder $subQuery) use ($request) {
                    $subQuery->where('slug', $request->string('color')->toString());
                });
            })
            ->when($request->filled('diameter'), function (Builder $query) use ($request) {
                $query->where('diameter_id', (int) $request->input('diameter'));
            })
            ->when($request->filled('price_min'), function (Builder $query) use ($request) {
                $query->where('price_gross', '>=', (float) $request->input('price_min'));
            })
            ->when($request->filled('price_max'), function (Builder $query) use ($request) {
                $query->where('price_gross', '<=', (float) $request->input('price_max'));
            });

        $sort = $request->input('sort', '');

        match ($sort) {
            'name_asc' => $productsQuery->orderBy('name'),
            'name_desc' => $productsQuery->orderByDesc('name'),
            'price_asc' => $productsQuery->orderBy('price_gross'),
            'price_desc' => $productsQuery->orderByDesc('price_gross'),
            default => $productsQuery->latest('id'),
        };

        $products = $productsQuery->paginate(9)->withQueryString();

        $brands = Product::query()
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds)
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $colors = Color::query()
            ->whereHas('products', function (Builder $query) use ($categoryIds) {
                $query->where('is_active', true)->whereIn('category_id', $categoryIds);
            })
            ->orderBy('sort_order')
            ->get();

        $diameters = Diameter::query()
            ->whereHas('products', function (Builder $query) use ($categoryIds) {
                $query->where('is_active', true)->whereIn('category_id', $categoryIds);
            })
            ->orderBy('sort_order')
            ->get();

        $carouselProducts = Product::query()
            ->with(['images', 'color', 'weight', 'diameter'])
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds)
            ->whereKeyNot($products->pluck('id'))
            ->latest('rating_avg')
            ->take(4)
            ->get();

        if ($carouselProducts->count() < 4) {
            $fallbackProducts = Product::query()
                ->with(['images', 'color', 'weight', 'diameter'])
                ->where('is_active', true)
                ->whereKeyNot($carouselProducts->pluck('id'))
                ->latest('rating_avg')
                ->take(4 - $carouselProducts->count())
                ->get();

            $carouselProducts = $carouselProducts->concat($fallbackProducts);
        }

        return view('shop.partials.shop.category', [
            'rootCategory' => $rootCategory,
            'activeCategory' => $activeCategory,
            'products' => $products,
            'brands' => $brands,
            'colors' => $colors,
            'diameters' => $diameters,
            'sort' => $sort,
            'carouselProducts' => $carouselProducts,
            'filters' => [
                'brand' => $request->input('brand'),
                'color' => $request->input('color'),
                'diameter' => $request->input('diameter'),
                'price_min' => $request->input('price_min'),
                'price_max' => $request->input('price_max'),
            ],
        ]);
    }

    private function resolveCategoryIdsForListing(Category $rootCategory): array
    {
        $childIds = $rootCategory->children->pluck('id')->all();

        if (!empty($childIds)) {
            return $childIds;
        }

        return [$rootCategory->id];
    }
}