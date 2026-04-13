@if ($products->isNotEmpty())
    <section class="py-4 border-top">
        <div class="container px-3">
            <div class="d-flex justify-content-center gap-2 mb-4">
                <button class="btn btn-custom btn-sm active" type="button">Recommended</button>
            </div>

            <div class="category-carousel" data-visible="4">
                <button
                    class="category-carousel-btn category-carousel-btn-prev btn btn-custom rounded-circle"
                    type="button"
                    aria-label="Previous products"
                >
                    &#8249;
                </button>

                <div class="category-carousel-viewport">
                    <div class="category-carousel-track">
                        @foreach ($products as $product)
                            <div class="category-carousel-slide">
                                @include('shop.partials.shop.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>

                <button
                    class="category-carousel-btn category-carousel-btn-next btn btn-custom rounded-circle"
                    type="button"
                    aria-label="Next products"
                >
                    &#8250;
                </button>
            </div>
        </div>
    </section>
@endif