@extends('layouts.shop')

@section('title', 'S&J - ' . $pageTitle)

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', ['category' => $category['slug']]) }}">
                        {{ $category['name'] }}
                    </a>
                </li>

                @if ($subcategory)
                    <li class="breadcrumb-item active" aria-current="page">{{ $subcategory['name'] }}</li>
                @endif
            </ol>
        </nav>
    </div>

    <div class="container px-3 py-3">
        <div class="row g-4">
            <aside class="col-md-2">
                @include('shop.partials.shop.category-sidebar', [
                    'currentCategory' => $category,
                    'currentSubcategory' => $subcategory,
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

                        <button class="btn btn-custom btn-sm" type="submit">Apply</button>
                    </div>
                </form>

                <p class="small text-muted mb-3">
                    {{ $pageTitle }}: {{ $products->count() }} products
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

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="small text-muted mb-0">
                        Showing {{ $products->count() }} products
                    </p>

                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @include('shop.partials.shop.bottom-carousel', ['products' => $carouselProducts])
@endsection