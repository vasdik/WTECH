@if ($products->isNotEmpty())
    <section class="py-4 border-top">
        <div class="container px-3">
            <div class="d-flex justify-content-center gap-2 mb-4">
                <button class="btn btn-custom btn-sm active" type="button">Recommended</button>
            </div>

            <div class="position-relative">
                <button
                    class="btn btn-custom rounded-circle position-absolute top-50 start-0 translate-middle-y z-1"
                    style="width: 45px; height: 45px;"
                    type="button"
                    onclick="scrollCategoryProducts(-1)"
                >
                    &#8249;
                </button>

                <div class="d-flex gap-3 overflow-hidden mx-5 justify-content-center" id="category-bottom-carousel">
                    @foreach ($products as $product)
                        <div class="flex-shrink-0" style="width: 200px;">
                            @include('shop.partials.shop.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <button
                    class="btn btn-custom rounded-circle position-absolute top-50 end-0 translate-middle-y z-1"
                    style="width: 45px; height: 45px;"
                    type="button"
                    onclick="scrollCategoryProducts(1)"
                >
                    &#8250;
                </button>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        function scrollCategoryProducts(direction) {
            const list = document.getElementById('category-bottom-carousel');
            if (!list) return;
            list.scrollBy({ left: direction * 220, behavior: 'smooth' });
        }
    </script>
    @endpush
@endif