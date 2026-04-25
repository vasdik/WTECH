<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\enum\Color;
use App\Models\enum\Diameter;
use App\Models\enum\FilamentType;
use App\Models\enum\Weight;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $productsQuery = Product::query()
            ->with(['images', 'category', 'color', 'weight', 'diameter']);

        $productsQuery
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q')->toString();

                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('name', 'ILIKE', "%{$q}%")
                        ->orWhere('brand', 'ILIKE', "%{$q}%")
                        ->orWhere('slug', 'ILIKE', "%{$q}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $selectedCategory = Category::query()
                    ->with('children')
                    ->find((int) $request->input('category_id'));

                if (! $selectedCategory) {
                    return;
                }

                $categoryIds = collect([$selectedCategory->id])
                    ->merge($selectedCategory->children->pluck('id'))
                    ->unique()
                    ->values();

                $query->whereIn('category_id', $categoryIds);
            })
            ->when($request->filled('color_id'), function ($query) use ($request) {
                $query->where('color_id', (int) $request->input('color_id'));
            })
            ->when($request->filled('weight_id'), function ($query) use ($request) {
                $query->where('weight_id', (int) $request->input('weight_id'));
            })
            ->when($request->filled('diameter_id'), function ($query) use ($request) {
                $query->where('diameter_id', (int) $request->input('diameter_id'));
            });

        $sort = $request->input('sort', 'latest');

        match ($sort) {
            'name_asc' => $productsQuery->orderBy('name'),
            'name_desc' => $productsQuery->orderByDesc('name'),
            'price_asc' => $productsQuery->orderBy('price_gross'),
            'price_desc' => $productsQuery->orderByDesc('price_gross'),
            default => $productsQuery->latest('id'),
        };

        return view('admin.products.index', [
            'products' => $productsQuery->paginate(12)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'colors' => Color::query()->orderBy('name')->get(),
            'weights' => Weight::query()->orderBy('sort_order')->get(),
            'diameters' => Diameter::query()->orderBy('sort_order')->get(),
            'filters' => [
                'q' => $request->input('q'),
                'category_id' => $request->input('category_id'),
                'color_id' => $request->input('color_id'),
                'weight_id' => $request->input('weight_id'),
                'diameter_id' => $request->input('diameter_id'),
                'sort' => $sort,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData(new Product([
            'tax_rate' => 23,
            'stock_qty' => 0,
            'is_active' => true,
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $slug = filled($validated['slug'] ?? null)
            ? $validated['slug']
            : $this->generateUniqueSlug($validated['name']);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'product_family_id' => null,
            'color_id' => $validated['color_id'] ?: null,
            'weight_id' => $validated['weight_id'] ?: null,
            'diameter_id' => $validated['diameter_id'] ?: null,
            'variant_label' => null,
            'name' => $validated['name'],
            'slug' => $slug,
            'brand' => $validated['brand'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'price_gross' => $validated['price_gross'],
            'tax_rate' => $validated['tax_rate'],
            'rating_avg' => 0,
            'rating_count' => 0,
            'stock_qty' => $validated['stock_qty'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->storeImages($request, $product);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('cart_success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'category', 'color']);

        return view('admin.products.edit', $this->formData($product));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);

        $slug = filled($validated['slug'] ?? null)
            ? $validated['slug']
            : $this->generateUniqueSlug($validated['name'], $product);

        $product->update([
            'category_id' => $validated['category_id'],
            'color_id' => $validated['color_id'] ?: null,
            'weight_id' => $validated['weight_id'] ?: null,
            'diameter_id' => $validated['diameter_id'] ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'brand' => $validated['brand'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'price_gross' => $validated['price_gross'],
            'tax_rate' => $validated['tax_rate'],
            'stock_qty' => $validated['stock_qty'],
            'is_active' => $request->boolean('is_active'),
        ]);
        $this->storeImages($request, $product);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('cart_success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->load('images');

        foreach ($product->images as $image) {
            $this->deletePhysicalImage($image);
            $image->delete();
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('cart_success', 'Product deleted successfully.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless((int) $image->product_id === (int) $product->id, 404);

        $wasPrimary = (bool) $image->is_primary;

        $this->deletePhysicalImage($image);
        $image->delete();

        if ($wasPrimary) {
            $newPrimary = $product->images()->orderBy('sort_order')->first();

            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('cart_success', 'Image deleted successfully.');
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'colors' => Color::query()->orderBy('name')->get(),
            'weights' => Weight::query()->orderBy('sort_order')->get(),
            'diameters' => Diameter::query()->orderBy('sort_order')->get(),
        ];
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $slugRule = 'nullable|string|max:255|unique:products,slug';
        if ($product) {
            $slugRule .= ',' . $product->id;
        }

      return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => explode('|', $slugRule),
            'brand' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'weight_id' => ['nullable', 'integer', 'exists:weights,id'],
            'diameter_id' => ['nullable', 'integer', 'exists:diameters,id'],
            'price_gross' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable'],
            'images.*' => ['image', 'max:5120'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?Product $ignore = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $counter = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function storeImages(Request $request, Product $product): void
    {
        $files = $request->file('images', []);

        if (empty($files)) {
            return;
        }

        if (! is_array($files)) {
            $files = [$files];
        }

        $currentMaxSort = (int) $product->images()->max('sort_order');
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'storage/' . $path,
                'alt_text' => $product->name,
                'sort_order' => $currentMaxSort + $index + 1,
                'is_primary' => ! $hasPrimary && $index === 0,
            ]);
        }
    }

    private function deletePhysicalImage(ProductImage $image): void
    {
        $path = $image->path;

        if (Str::startsWith($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        }
    }
}