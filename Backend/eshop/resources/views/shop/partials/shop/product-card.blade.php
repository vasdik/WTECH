<a href="#" class="text-decoration-none text-dark">
    <div class="card h-100">
        @if (!empty($product['image']))
            <img
                src="{{ asset($product['image']) }}"
                class="card-img-top"
                alt="{{ $product['name'] }}"
                style="height: 180px; object-fit: scale-down;"
            >
        @else
            <div class="img-placeholder" style="height: 180px;">Product image</div>
        @endif

        <div class="card-body p-2">
            <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">
                {{ number_format($product['rating'], 1) }}/5
            </span>
            <p class="mb-0 fw-bold small">{{ $product['brand'] }}</p>
            <p class="text-muted small mb-2">{{ $product['name'] }}</p>
            <p class="fw-bold mb-2">{{ number_format($product['price'], 2) }} €</p>
            <button class="btn btn-custom btn-sm w-100" type="button">Add to Cart</button>
        </div>
    </div>
</a>