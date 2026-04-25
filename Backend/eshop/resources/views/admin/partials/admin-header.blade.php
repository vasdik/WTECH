@php
    $cartCount = app(\App\Services\CartService::class)->count();
@endphp

<!-- ====================
     HEADER: Logo | Search bar | Nav buttons
    ==================== -->
<header class="border-bottom py-2 sticky-header">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-end mb-1">
            <a href="#" class="text-secondary small text-decoration-none">Admin Panel</a>
        </div>
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 order-1">
                <img src="{{ asset('images/icons/logo-cropped.svg') }}" alt="Logo" style="height: 60px; width: auto;">
            </a>
            <!-- Search -->
            <div class="w-100 w-md-auto order-3 order-md-2 flex-md-grow-1">
                <form action="{{ route('search.index') }}" method="GET">
                    <input
                        type="search"
                        name="q"
                        class="form-control"
                        placeholder="Search products..."
                        value="{{ request('q') }}"
                    >
                </form>
            </div>
            <!-- Buttons -->
            <div class="d-flex gap-2 flex-shrink-0 order-2 ms-auto">
                @guest
                    <a href="{{ route('login') }}" id="header-login-btn" class="btn btn-custom btn-sm">Log in</a>
                @endguest

                @auth
                    <a href="{{ route('profile.edit') }}" id="header-profile-btn" class="btn btn-custom btn-sm">Profile</a>
                @endauth

                <a href="{{ route('saved') }}" class="btn btn-custom btn-sm">Saved</a>
                <a href="{{ route('cart') }}" class="btn btn-custom btn-sm" id="header-cart-button">
                    Cart<span id="header-cart-count">{{ $cartCount > 0 ? ' (' . $cartCount . ')' : '' }}</span>
                </a>
            </div>
        </div>
    </div>
</header>