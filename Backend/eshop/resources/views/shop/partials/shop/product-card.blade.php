@php
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
@endphp

<div class="card h-100 d-flex flex-column">
    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
        @if ($primaryImage)
            <img
                src="{{ asset($primaryImage->path) }}"
                class="card-img-top"
                alt="{{ $primaryImage->alt_text ?? $product->name }}"
                style="height: 180px; object-fit: scale-down;"
            >
        @else
            <div class="img-placeholder" style="height: 180px;">Product image</div>
        @endif
    </a>

    <div class="card-body p-2 d-flex flex-column flex-grow-1">
        <div>
            @if ($product->rating_avg)
                <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">
                    {{ number_format((float) $product->rating_avg, 1) }}/5
                </span>
            @endif

            <p class="mb-0 fw-bold small">{{ $product->brand }}</p>
            <p class="text-muted small mb-2">{{ $product->name }}</p>
        </div>

        <div class=" mt-auto">
            <p class="fw-bold mb-0">{{ number_format((float) $product->price_gross, 2) }} €</p>

            @include('shop.partials.cart.cart-controls', [
                'product' => $product,
                'buttonClass' => 'btn btn-custom btn-sm',
                'fullWidth' => true,
            ])
        </div>
    </div>
</div>