@extends('layouts.shop')

@section('title', 'S&J - ' . $product->name)

@section('content')
    @php
        $rootCategory = $product->category->parent ?? $product->category;
        $leafCategory = $product->category;
    @endphp

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

                @if ($leafCategory->id !== $rootCategory->id)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', ['category' => $rootCategory->slug, 'subcategory' => $leafCategory->slug]) }}">
                            {{ $leafCategory->name }}
                        </a>
                    </li>
                @endif

                <li class="breadcrumb-item active" aria-current="page">
                    {{ $product->name }}
                </li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <div class="row g-4">
                <div class="col-md-5">
                    @if ($primaryImage)
                        <img
                            id="main-product-image"
                            src="{{ asset($primaryImage->path) }}"
                            alt="{{ $primaryImage->alt_text ?? $product->name }}"
                            class="card-img-top"
                            style="height: 480px; object-fit: scale-down;"
                        >
                    @else
                        <div class="img-placeholder d-flex align-items-center justify-content-center" style="height: 480px;">
                            Product image
                        </div>
                    @endif

                    <p class="small text-muted mb-2">Product photos</p>

                    @if ($galleryImages->isNotEmpty())
                        <div class="d-flex align-items-center gap-2 overflow-hidden position-relative">
                            <button class="btn btn-custom btn-sm flex-shrink-0" type="button" onclick="scrollThumbs(-1)">
                                &#8249;
                            </button>

                            <div class="d-flex gap-2 overflow-auto flex-nowrap" id="product-thumbs">
                                @foreach ($galleryImages as $image)
                                    <img
                                        src="{{ asset($image->path) }}"
                                        alt="{{ $image->alt_text ?? $product->name }}"
                                        class="card-img-top border rounded product-thumb"
                                        style="height: 80px; width: 80px; object-fit: scale-down; cursor: pointer;"
                                        onclick="changeMainImage('{{ asset($image->path) }}', '{{ e($image->alt_text ?? $product->name) }}')"
                                    >
                                @endforeach
                            </div>

                            <button class="btn btn-custom btn-sm flex-shrink-0" type="button" onclick="scrollThumbs(1)">
                                &#8250;
                            </button>
                        </div>
                    @endif
                </div>

                <div class="col-md-7">
                    <p class="text-muted small mb-1">Product Brand: {{ $product->brand }}</p>
                    <h4 class="fw-bold mb-2">{{ $product->name }}</h4>

                    @if ($product->rating_avg)
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ number_format((float) $product->rating_avg, 1) }}/5</span>
                        </div>
                    @endif

                    <p class="mb-0">
                        Price:
                        <span class="fw-bold fs-5">{{ number_format($displayPrice, 2) }} €</span>
                    </p>
                    <p class="text-muted small mb-3">
                        Price without taxes: {{ number_format($priceNet, 2) }} € tax {{ number_format((float) $product->tax_rate, 0) }}%
                    </p>

                    @if ($colorProducts->isNotEmpty())
                        <p class="small mb-2">Colors:</p>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            @foreach ($colorProducts as $colorProduct)
                                @php
                                    $colorImage = $colorProduct->images->firstWhere('is_primary', true) ?? $colorProduct->images->first();
                                @endphp

                                <a
                                    href="{{ route('products.show', $colorProduct) }}"
                                    class="text-decoration-none {{ $product->id === $colorProduct->id ? 'border border-2 border-dark rounded p-1' : 'p-1' }}"
                                    title="{{ $colorProduct->color?->name }}"
                                >
                                    @if ($colorImage)
                                        <img
                                            src="{{ asset($colorImage->path) }}"
                                            alt="{{ $colorProduct->color?->name }}"
                                            style="height: 60px; width: 60px; object-fit: scale-down;"
                                        >
                                    @else
                                        <div class="img-placeholder" style="width: 60px; height: 60px;">
                                            {{ $colorProduct->color?->name }}
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($variantProducts->isNotEmpty())
                        <p class="small mb-2">Variants:</p>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            @foreach ($variantProducts as $variantProduct)
                                <a
                                    href="{{ route('products.show', $variantProduct) }}"
                                    class="btn {{ $product->id === $variantProduct->id ? 'btn-custom' : 'btn-outline-custom' }} btn-sm"
                                >
                                    {{ $variantProduct->variant_label }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <p class="small mb-0">
                        @if ($stockQty > 0)
                            <span class="text-success fw-bold">In stock</span>
                        @else
                            <span class="text-danger fw-bold">Out of stock</span>
                        @endif
                    </p>
                    <p class="small text-muted mb-3">Estimated delivery time 3–5 days</p>

                    <div class="d-flex gap-2 mb-2 flex-wrap">
                        <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                            @include('shop.partials.cart.cart-controls', [
                                'product' => $product,
                                'fullWidth' => false,
                            ])

                            <a href="{{ route('saved') }}" class="btn btn-custom">Saved</a>
                            <a href="{{ route('cart') }}" class="btn btn-custom">Cart</a>
                        </div>
                    </div>

                    <p class="small text-muted">Slovakia: free shipping on orders over €50.</p>
                </div>
            </div>
        </div>

        @if ($product->filamentDetail)
            <section class="bg-light border-top border-bottom py-4 my-3">
                <div class="container px-3">
                    <h5 class="text-center fw-bold mb-4">Product information and technical specs</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-muted small" style="width:40%;">Weight:</td>
                                        <td class="small">{{ $product->weight?->label ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small">Manufacturer (brand):</td>
                                        <td class="small">{{ $product->brand }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small">Color:</td>
                                        <td class="small">{{ $product->color?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small">Diameter:</td>
                                        <td class="small">{{ $product->diameter?->label ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-muted small" style="width:50%;">Product type:</td>
                                        <td class="small">
                                            {{ $product->filamentDetail->filamentType?->name ? $product->filamentDetail->filamentType->name . ' filament' : 'Filament' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small">Type:</td>
                                        <td class="small">Spool</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small fw-bold">Recommended printing temperature:</td>
                                        <td class="small">
                                            {{ $product->filamentDetail->recommended_nozzle_temp_min ?? '-' }}
                                            –
                                            {{ $product->filamentDetail->recommended_nozzle_temp_max ?? '-' }} °C
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small fw-bold">Recommended heating-pad temperature:</td>
                                        <td class="small">
                                            {{ $product->filamentDetail->recommended_bed_temp_min ?? '-' }}
                                            –
                                            {{ $product->filamentDetail->recommended_bed_temp_max ?? '-' }} °C
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <div class="container px-3 py-4">
            <h5 class="fw-bold mb-4">Description</h5>

            @if ($product->family?->shared_description)
                <p class="small text-muted mb-4">{{ $product->family->shared_description }}</p>
            @elseif ($product->description)
                <p class="small text-muted mb-4">{{ $product->description }}</p>
            @endif

            @if ($product->filamentDetail?->material_note)
                <h6 class="fw-bold">Material note</h6>
                <p class="small text-muted mb-4">{{ $product->filamentDetail->material_note }}</p>
            @endif
        </div>

        @include('shop.partials.shop.bottom-carousel', ['products' => $relatedProducts])
    </main>
@endsection

@push('scripts')
<script>
    function scrollThumbs(direction) {
        const container = document.getElementById('product-thumbs');
        if (!container) return;

        container.scrollBy({
            left: direction * 120,
            behavior: 'smooth'
        });
    }

    function changeMainImage(src, alt) {
        const mainImage = document.getElementById('main-product-image');
        if (!mainImage) return;

        mainImage.src = src;
        mainImage.alt = alt;
    }
</script>
@endpush