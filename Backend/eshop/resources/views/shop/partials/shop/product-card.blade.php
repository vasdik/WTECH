@php
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $variant = $product->defaultVariant ?? $product->variants->first();
    $displayPrice = $variant?->price_gross ?? $product->price_gross;
@endphp

<a href="#" class="text-decoration-none text-dark">
    <div class="card h-100">
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

        <div class="card-body p-2">
            @if ($product->rating_avg)
                <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">
                    {{ number_format((float) $product->rating_avg, 1) }}/5
                </span>
            @endif

            <p class="mb-0 fw-bold small">{{ $product->brand }}</p>
            <p class="text-muted small mb-2">{{ $product->name }}</p>
            <p class="fw-bold mb-2">{{ number_format((float) $displayPrice, 2) }} €</p>

            <button class="btn btn-custom btn-sm w-100" type="button">
                Add to Cart
            </button>
        </div>
    </div>
</a>