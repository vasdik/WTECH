<!-- ====================
     HEADER: Logo | Search bar | Nav buttons
    ==================== -->
<header class="border-bottom py-2 sticky-header">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-end mb-1">
            <a href="#" class="text-secondary small text-decoration-none">Support</a>
        </div>
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 order-1">
                <img src="{{ asset('images/icons/logo-cropped.svg') }}" alt="Logo" style="height: 60px; width: auto;">
            </a>
            <!-- Search -->
            <div class="w-100 w-md-auto order-3 order-md-2 flex-md-grow-1">
                <input type="search" class="form-control" placeholder="Search bar">
            </div>
            <!-- Buttons -->
            <div class="d-flex gap-2 flex-shrink-0 order-2 ms-auto">
                <a href="{{ route('login') }}" id="header-login-btn" class="btn btn-custom btn-sm">Log in</a>
                <a href="{{ route('saved') }}" class="btn btn-custom btn-sm">Saved</a>
                <a href="{{ route('cart') }}" class="btn btn-custom btn-sm">Cart</a>
            </div>
        </div>
    </div>
</header>