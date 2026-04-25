@extends('layouts.admin')

@section('title', 'Admin - Products')

@section('admin_search')
    <form method="GET" action="{{ route('admin.products.index') }}">
        <input
            type="search"
            name="q"
            class="form-control"
            placeholder="Search name / brand / slug"
            value="{{ $filters['q'] }}"
        >
    </form>
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>
@endsection

@section('content')
    @php
        $adminSteps = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['key' => 'products', 'label' => 'Products', 'route' => route('admin.products.index')],
            ['key' => 'create', 'label' => 'Add Product', 'route' => route('admin.products.create')],
        ];
    @endphp

    @include('admin.partials.admin-nav-bar', [
        'steps' => $adminSteps,
        'currentStep' => 'products',
    ])

    <h2 class="fw-bold mb-4">Admin Panel</h2>

    <form method="GET" action="{{ route('admin.products.index')}}" class="mb-3">
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

            <select name="weight_id" class="form-select form-select-sm" style="width:auto;">
                <option value="">Weight</option>
                @foreach ($weights as $weight)
                    <option value="{{ $weight->id }}" @selected((string) $filters['weight_id'] === (string) $weight->id)>
                        {{ $weight->label }}
                    </option>
                @endforeach
            </select>

            <select name="diameter_id" class="form-select form-select-sm" style="width:auto;">
                <option value="">Diameter</option>
                @foreach ($diameters as $diameter)
                    <option value="{{ $diameter->id }}" @selected((string) $filters['diameter_id'] === (string) $diameter->id)>
                        {{ $diameter->label }}
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
@endsection