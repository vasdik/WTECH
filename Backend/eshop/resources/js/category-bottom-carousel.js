function initCategoryCarousels() {
    document.querySelectorAll('.category-carousel').forEach((carousel) => {
        if (carousel.dataset.initialized === 'true') {
            return;
        }

        const viewport = carousel.querySelector('.category-carousel-viewport');
        const track = carousel.querySelector('.category-carousel-track');
        const prevButton = carousel.querySelector('.category-carousel-btn-prev');
        const nextButton = carousel.querySelector('.category-carousel-btn-next');

        if (!viewport || !track || !prevButton || !nextButton) {
            return;
        }

        const originalSlides = Array.from(track.children).map((slide) => slide.outerHTML);
        const total = originalSlides.length;

        if (!total) {
            return;
        }

        carousel.dataset.initialized = 'true';

        let currentIndex = 0;
        let isAnimating = false;

        const gap = 16;
        const duration = 320;

        function getVisibleCount() {
            const desktopVisible = parseInt(carousel.dataset.visible || '4', 10);

            if (window.innerWidth < 576) return 1;
            if (window.innerWidth < 768) return 2;
            if (window.innerWidth < 992) return 3;
            return desktopVisible;
        }

        function getEffectiveVisibleCount() {
            return Math.min(getVisibleCount(), total);
        }

        function mod(index, base) {
            return ((index % base) + base) % base;
        }

        function buildWindowIndices() {
            const visible = getEffectiveVisibleCount();

            if (total <= visible) {
                return Array.from({ length: total }, (_, i) => i);
            }

            const indices = [];

            indices.push(mod(currentIndex - 1, total));

            for (let i = 0; i < visible; i++) {
                indices.push(mod(currentIndex + i, total));
            }

            indices.push(mod(currentIndex + visible, total));

            return indices;
        }

        function renderWindow() {
            const visible = getEffectiveVisibleCount();

            if (total <= visible) {
                track.innerHTML = originalSlides.join('');
                prevButton.style.display = 'none';
                nextButton.style.display = 'none';
            } else {
                const indices = buildWindowIndices();
                track.innerHTML = indices.map((index) => originalSlides[index]).join('');
                prevButton.style.display = '';
                nextButton.style.display = '';
            }

            measureSlides();
            resetPosition();
        }

        function measureSlides() {
            const slides = Array.from(track.children);
            const visible = getEffectiveVisibleCount();
            const viewportWidth = viewport.clientWidth;
            const slideWidth = (viewportWidth - gap * (visible - 1)) / visible;

            track.style.gap = `${gap}px`;

            slides.forEach((slide) => {
                slide.style.width = `${slideWidth}px`;
                slide.style.flex = `0 0 ${slideWidth}px`;
            });

            return slideWidth;
        }

        function resetPosition() {
            const visible = getEffectiveVisibleCount();

            track.style.transition = 'none';

            if (total <= visible) {
                track.style.transform = 'translateX(0)';
            } else {
                const slideWidth = measureSlides();
                const offset = slideWidth + gap;
                track.style.transform = `translateX(-${offset}px)`;
            }
        }

        function next() {
            if (isAnimating) return;

            const visible = getEffectiveVisibleCount();

            if (total <= visible) {
                return;
            }

            const slideWidth = measureSlides();
            const offset = slideWidth + gap;

            isAnimating = true;
            track.style.transition = `transform ${duration}ms ease`;
            track.style.transform = `translateX(-${2 * offset}px)`;

            window.setTimeout(() => {
                currentIndex = mod(currentIndex + 1, total);
                renderWindow();
                isAnimating = false;
            }, duration);
        }

        function prev() {
            if (isAnimating) return;

            const visible = getEffectiveVisibleCount();

            if (total <= visible) {
                return;
            }

            const slideWidth = measureSlides();
            const offset = slideWidth + gap;

            isAnimating = true;
            track.style.transition = `transform ${duration}ms ease`;
            track.style.transform = 'translateX(0)';

            window.setTimeout(() => {
                currentIndex = mod(currentIndex - 1, total);
                renderWindow();
                isAnimating = false;
            }, duration);
        }

        prevButton.addEventListener('click', prev);
        nextButton.addEventListener('click', next);

        window.addEventListener('resize', () => {
            if (isAnimating) return;
            renderWindow();
        });

        renderWindow();
    });
}

document.addEventListener('DOMContentLoaded', initCategoryCarousels);

export default initCategoryCarousels;