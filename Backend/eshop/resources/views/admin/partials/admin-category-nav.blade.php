<!-- ====================
     CATEGORY NAVIGATION
     Row of category images with labels
    ==================== -->
<nav class="border-bottom py-3 nav-custom">
    <div class="container px-3">
        <div class="row row-cols-7 g-2 text-center">


            <div class="col">
                <a href="{{ route('categories.show', ['category' => 'printers']) }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <img src="{{ asset('images/icons/nav_bar_printers.png') }}" alt="Printers" class="nav-icon mb-1">
                    <small>Printers</small>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('categories.show', ['category' => 'filaments']) }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <img src="{{ asset('images/icons/nav_bar_filaments.png') }}" alt="Filaments" class="nav-icon mb-1">
                    <small>Filaments</small>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('categories.show', ['category' => 'resins']) }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <img src="{{ asset('images/icons/nav_bar_resins.png') }}" alt="Resins" class="nav-icon mb-1">
                    <small>Resins</small>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('categories.show', ['category' => 'accessories']) }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <img src="{{ asset('images/icons/nav_bar_acc.png') }}" alt="Accessories" class="nav-icon mb-1">
                    <small>Accessories</small>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('categories.show', ['category' => 'tools']) }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <img src="{{ asset('images/icons/nav_bar_tools.png') }}" alt="Tools" class="nav-icon mb-1">
                    <small>Tools</small>
                </a>
            </div>

        </div>
    </div>
</nav>