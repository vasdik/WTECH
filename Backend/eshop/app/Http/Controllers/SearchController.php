<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = Str::of((string) $request->input('q'))->squish()->toString();
        $sort = (string) $request->input('sort', 'relevance');

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
            ->where('is_active', true);

        if ($q !== '') {
            $contains = '%' . $q . '%';
            $prefix = $q . '%';

            $productsQuery->where(function (Builder $query) use ($contains) {
                $query->where('name', 'ILIKE', $contains)
                    ->orWhere('brand', 'ILIKE', $contains)
                    ->orWhere('short_description', 'ILIKE', $contains)
                    ->orWhere('description', 'ILIKE', $contains);
            });

            if ($sort === 'relevance') {
                $productsQuery->orderByRaw(
                    "CASE
                        WHEN name ILIKE ? THEN 1
                        WHEN brand ILIKE ? THEN 2
                        WHEN name ILIKE ? THEN 3
                        WHEN short_description ILIKE ? THEN 4
                        WHEN description ILIKE ? THEN 5
                        ELSE 6
                    END",
                    [$q, $q, $prefix, $contains, $contains]
                );
            }
        } else {
            $productsQuery->whereRaw('1 = 0');
        }

        match ($sort) {
            'name_asc' => $productsQuery->orderBy('name'),
            'name_desc' => $productsQuery->orderByDesc('name'),
            'price_asc' => $productsQuery->orderBy('price_gross'),
            'price_desc' => $productsQuery->orderByDesc('price_gross'),
            default => $productsQuery->latest('id'),
        };

        $products = $productsQuery->paginate(9)->withQueryString();

        return view('shop.partials.shop.search-results', [
            'q' => $q,
            'sort' => $sort,
            'products' => $products,
        ]);
    }
}