@extends('layouts.shop')

@section('title', 'Admin - Products')

@section('content')
    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <h2 class="fw-bold mb-4">Admin Panel</h2>

            <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
                <div class="d-flex flex-wrap gap-2">
                    <input
                        type="search"
                        name="q"
                        class="form-control"
                        placeholder="Search name / brand / slug"
                        value="{{ $filters['q'] }}"
                        style="max-width: 260px;"
                    >

                    <select name="category_id" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="color_id" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Color</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}" @selected((string) $filters['color_id'] === (string) $color->id)>
                                {{ $color->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sort" class="form-select form-select-sm" style="width:auto;">
                        <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                        <option value="name_asc" @selected($filters['sort'] === 'name_asc')>A to Z</option>
                        <option value="name_desc" @selected($filters['sort'] === 'name_desc')>Z to A</option>
                        <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Price: low to high</option>
                        <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Price: high to low</option>
                    </select>

                    <button class="btn btn-custom btn-sm">Apply</button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-custom btn-sm">Add Product</a>
                </div>
            </form>

            <p class="small text-muted mb-3">
                Products:
                {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                out of {{ $products->total() }}
            </p>

            <div class="row row-cols-2 row-cols-md-3 g-3 mb-4">
                @foreach ($products as $product)
                    @php
                        $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                    @endphp

                    <div class="col">
                        <div class="card h-100">
                            @if ($image)
                                <img
                                    src="{{ asset($image->path) }}"
                                    alt="{{ $image->alt_text ?? $product->name }}"
                                    class="card-img-top"
                                    style="height: 180px; object-fit: scale-down;"
                                >
                            @else
                                <div class="img-placeholder" style="height: 180px;">Product image</div>
                            @endif

                            <div class="card-body p-2">
                                <p class="mb-0 fw-bold small">{{ $product->brand }}</p>
                                <p class="text-muted small mb-2">{{ $product->name }}</p>
                                <p class="fw-bold mb-2">{{ number_format((float) $product->price_gross, 2) }} €</p>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-custom btn-sm w-100">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="small text-muted mb-0">
                    Products:
                    {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                    out of {{ $products->total() }}
                </p>

                {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </main>
@endsection