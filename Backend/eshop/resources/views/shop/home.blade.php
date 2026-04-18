@extends('layouts.shop')

@section('title', 'S&J - 3D Printing Shop')

@section('content')



<!-- ====================
     BREADCRUMB
    ==================== -->
<div class="container px-3 py-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.html">Main page</a></li>
        </ol>
    </nav>
</div>


<!-- ====================
     HERO BANNER / CAROUSEL
     New/Trending Items
    ==================== -->
<main class="flex-grow-1">

<section class="bg-light py-3">
    <div class="container-fluid px-0">

        <p class="text-center text-muted small mb-2">New/Trending Items</p>

        <div id="heroBanner" class="carousel slide w-100" data-bs-ride="carousel">

            <!-- Slides -->
            <div class="carousel-inner">

                <!-- SLIDE 1 -->
                <div class="carousel-item active text-center">
                    <img src="{{ asset('Images/Banners/Filaments_Banner_1024x419.jpg') }}"
                         class="d-block w-100"
                         style="height: 450px; object-fit: cover;">
                </div>

                <!-- SLIDE 2 -->
                <div class="carousel-item text-center">
                    <img src="{{ asset('Images/Banners/banner_printers.png') }}"
                         class="d-block w-100"
                         style="height: 450px; object-fit: cover;">
                </div>

                <!-- SLIDE 3 -->
                <div class="carousel-item text-center">
                    <img src="{{ asset('Images/Banners/3d-resin-printing-course-banner.png') }}"
                         class="d-block w-100"
                         style="height: 450px; object-fit: cover;">
                </div>

            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#heroBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </div>
</section>

</main>


<!-- ====================
     PRODUCT SECTION
     Tabs: Best sellers | Sales | New products
     + scrollable product cards
    ==================== -->
    @include('shop.partials.shop.bottom-carousel', ['products' => $carouselProducts])
</main>
@endsection