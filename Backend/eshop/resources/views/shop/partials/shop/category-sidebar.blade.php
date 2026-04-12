<div class="sticky-sidebar">
    <p class="fw-bold mb-2">Categories</p>

    <ul class="list-unstyled small">
        <li class="mb-1">
            <a
                href="{{ route('categories.show', ['category' => $rootCategory->slug]) }}"
                class="text-decoration-none {{ $activeCategory->id === $rootCategory->id ? 'fw-bold text-dark' : 'text-dark' }}"
            >
                {{ $rootCategory->name }}
            </a>
        </li>

        @foreach ($rootCategory->children as $child)
            <li class="mb-1 ms-2">
                <a
                    href="{{ route('categories.show', ['category' => $rootCategory->slug, 'subcategory' => $child->slug]) }}"
                    class="text-decoration-none {{ $activeCategory->id === $child->id ? 'fw-bold text-dark' : 'text-secondary' }}"
                >
                    {{ $child->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>