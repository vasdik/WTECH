@extends('layouts.shop')

@section('title', 'S&J - ' . $activeCategory->name)

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Main page</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', ['category' => $rootCategory->slug]) }}">
                        {{ $rootCategory->name }}
                    </a>
                </li>

                @if ($activeCategory->id !== $rootCategory->id)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $activeCategory->name }}
                    </li>
                @endif
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        <div class="container px-3 py-3">
            <div class="row g-4">
                <aside class="col-md-2">
                    @include('shop.partials.shop.category-sidebar', [
                        'currentCategory' => $rootCategory,
                        'currentSubcategory' => $activeCategory->id !== $rootCategory->id ? $activeCategory : null,
                    ])
                </aside>

                <div class="col-md-10">
                    <form method="GET" class="mb-3">
                        <p class="fw-bold mb-2">Filter and sort</p>

                        <div class="d-flex flex-wrap gap-2">
                            <select name="sort" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Best match</option>
                                <option value="name_asc" @selected($sort === 'name_asc')>A to Z</option>
                                <option value="name_desc" @selected($sort === 'name_desc')>Z to A</option>
                                <option value="price_asc" @selected($sort === 'price_asc')>Price: low to high</option>
                                <option value="price_desc" @selected($sort === 'price_desc')>Price: high to low</option>
                            </select>

                            <select name="brand" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Product brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand }}" @selected(($filters['brand'] ?? '') === $brand)>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="color" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Color</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->slug }}" @selected(($filters['color'] ?? '') === $color->slug)>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="diameter" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Diameter</option>
                                @foreach ($diameters as $diameter)
                                    <option value="{{ $diameter->id }}" @selected((string) ($filters['diameter'] ?? '') === (string) $diameter->id)>
                                        {{ $diameter->label }}
                                    </option>
                                @endforeach
                            </select>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="price_min"
                                value="{{ $filters['price_min'] }}"
                                class="form-control form-control-sm"
                                placeholder="Min €"
                                style="width: 110px;"
                            >

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="price_max"
                                value="{{ $filters['price_max'] }}"
                                class="form-control form-control-sm"
                                placeholder="Max €"
                                style="width: 110px;"
                            >

                            <button class="btn btn-custom btn-sm" type="submit">Apply</button>

                            <a
                                href="{{ $activeCategory->id === $rootCategory->id
                                    ? route('categories.show', ['category' => $rootCategory->slug])
                                    : route('categories.show', ['category' => $rootCategory->slug, 'subcategory' => $activeCategory->slug]) }}"
                                class="btn btn-outline-custom btn-sm"
                            >
                                Reset
                            </a>
                        </div>
                    </form>

                    <p class="small text-muted mb-3">
                        {{ $activeCategory->name }}:
                        {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                        out of {{ $products->total() }} products
                    </p>

                    <div class="row row-cols-2 row-cols-md-3 g-3 mb-4">
                        @forelse ($products as $product)
                            <div class="col">
                                @include('shop.partials.shop.product-card', ['product' => $product])
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    No products found.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <p class="small text-muted mb-0">
                            {{ $activeCategory->name }}:
                            {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                            out of {{ $products->total() }} products
                        </p>

                        {{ $products->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>

        @include('shop.partials.shop.bottom-carousel', ['products' => $carouselProducts])
    </main>
@endsection