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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.category-carousel').forEach((carousel) => {
                const viewport = carousel.querySelector('.category-carousel-viewport');
                const track = carousel.querySelector('.category-carousel-track');
                const prevButton = carousel.querySelector('.category-carousel-btn-prev');
                const nextButton = carousel.querySelector('.category-carousel-btn-next');

                if (!viewport || !track || !prevButton || !nextButton) {
                    return;
                }

                const originalSlides = Array.from(track.children);
                const originalCount = originalSlides.length;

                if (originalCount === 0) {
                    return;
                }

                let visible = parseInt(carousel.dataset.visible || '4', 10);
                let currentIndex = 0;
                let slideWidth = 0;
                let gap = 0;
                let isAnimating = false;

                function getVisibleCount() {
                    if (window.innerWidth < 576) return 1;
                    if (window.innerWidth < 768) return 2;
                    if (window.innerWidth < 992) return 3;
                    return visible;
                }

                function clearClones() {
                    track.querySelectorAll('.is-clone').forEach((clone) => clone.remove());
                }

                function buildClones() {
                    clearClones();

                    const currentSlides = Array.from(track.children).filter(
                        (slide) => !slide.classList.contains('is-clone')
                    );

                    const visibleNow = getVisibleCount();
                    const prependCount = Math.min(visibleNow, currentSlides.length);
                    const appendCount = Math.min(visibleNow, currentSlides.length);

                    const headClones = currentSlides.slice(0, appendCount).map((slide) => {
                        const clone = slide.cloneNode(true);
                        clone.classList.add('is-clone');
                        return clone;
                    });

                    const tailClones = currentSlides.slice(-prependCount).map((slide) => {
                        const clone = slide.cloneNode(true);
                        clone.classList.add('is-clone');
                        return clone;
                    });

                    tailClones.forEach((clone) => track.insertBefore(clone, track.firstChild));
                    headClones.forEach((clone) => track.appendChild(clone));

                    currentIndex = visibleNow;
                }

                function measure() {
                    const viewportWidth = viewport.clientWidth;
                    const visibleNow = getVisibleCount();

                    gap = 16;
                    slideWidth = (viewportWidth - gap * (visibleNow - 1)) / visibleNow;

                    track.style.gap = `${gap}px`;

                    Array.from(track.children).forEach((slide) => {
                        slide.style.width = `${slideWidth}px`;
                    });
                }

                function setPosition(animate = true) {
                    track.style.transition = animate ? 'transform 0.35s ease' : 'none';
                    const offset = currentIndex * (slideWidth + gap);
                    track.style.transform = `translateX(-${offset}px)`;
                }

                function rebuild() {
                    buildClones();
                    measure();
                    setPosition(false);
                }

                nextButton.addEventListener('click', () => {
                    if (isAnimating) return;
                    isAnimating = true;
                    currentIndex += 1;
                    setPosition(true);
                });

                prevButton.addEventListener('click', () => {
                    if (isAnimating) return;
                    isAnimating = true;
                    currentIndex -= 1;
                    setPosition(true);
                });

                track.addEventListener('transitionend', () => {
                    const visibleNow = getVisibleCount();

                    if (currentIndex >= originalCount + visibleNow) {
                        currentIndex = visibleNow;
                        setPosition(false);
                    }

                    if (currentIndex < visibleNow) {
                        currentIndex = originalCount + visibleNow - 1;
                        setPosition(false);
                    }

                    isAnimating = false;
                });

                window.addEventListener('resize', () => {
                    rebuild();
                });

                rebuild();
            });
        });
    </script>
    @endpush
@endif