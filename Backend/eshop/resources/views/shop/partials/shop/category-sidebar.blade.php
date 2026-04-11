<div class="sticky-sidebar">
    <p class="fw-bold mb-2">Categories</p>

    <ul class="list-unstyled small">
        <li class="mb-2">
            <a
                href="{{ route('categories.show', ['category' => $currentCategory['slug']]) }}"
                class="text-decoration-none {{ $currentSubcategory ? 'text-secondary' : 'fw-bold text-dark' }}"
            >
                {{ $currentCategory['name'] }}
            </a>
        </li>

        @foreach ($currentCategory['subcategories'] as $item)
            <li class="mb-1 ms-2">
                <a
                    href="{{ route('categories.show', ['category' => $currentCategory['slug'], 'subcategory' => $item['slug']]) }}"
                    class="text-decoration-none {{ $currentSubcategory && $currentSubcategory['slug'] === $item['slug'] ? 'fw-bold text-dark' : 'text-secondary' }}"
                >
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>