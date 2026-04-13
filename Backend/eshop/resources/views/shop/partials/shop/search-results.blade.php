@extends('layouts.shop')

@section('title', 'S&J - Search')

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Main page</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Search</li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <h4 class="fw-bold mb-3">Search results</h4>

            <form method="GET" action="{{ route('search.index') }}" class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        class="form-control"
                        placeholder="Search products..."
                        style="max-width: 420px;"
                    >

                    <select name="sort" class="form-select" style="width: auto;">
                        <option value="relevance" @selected($sort === 'relevance')>Best match</option>
                        <option value="name_asc" @selected($sort === 'name_asc')>A to Z</option>
                        <option value="name_desc" @selected($sort === 'name_desc')>Z to A</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price: low to high</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price: high to low</option>
                    </select>

                    <button type="submit" class="btn btn-custom">Search</button>
                </div>
            </form>

            @if ($q === '')
                <div class="alert alert-light border">
                    Start typing a product name, brand, or keyword.
                </div>
            @else
                <p class="small text-muted mb-3">
                    Results for <strong>"{{ $q }}"</strong>:
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
                            <div class="alert alert-light border mb-0">
                                No products found for <strong>"{{ $q }}"</strong>.
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($products->total() > 0)
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <p class="small text-muted mb-0">
                            Results for <strong>"{{ $q }}"</strong>:
                            {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                            out of {{ $products->total() }} products
                        </p>

                        {{ $products->onEachSide(1)->links() }}
                    </div>
                @endif
            @endif
        </div>
    </main>
@endsection