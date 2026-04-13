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
<section class="py-4">
    <div class="container px-3">

        <!-- Tab buttons -->
        <div class="d-flex justify-content-center gap-2 mb-4">
            <button class="btn btn-custom btn-sm active" id="tab-bestsellers" onclick="showTab('bestsellers')">Best sellers</button>
            <button class="btn btn-custom btn-sm" id="tab-sales" onclick="showTab('sales')">Sales</button>
            <button class="btn btn-custom btn-sm" id="tab-new" onclick="showTab('new')">New products</button>
        </div>

        <!-- Product carousel wrapper -->
        <div class="position-relative">

            <!-- Previous button -->
            <button class="btn btn-custom rounded-circle position-absolute top-50 start-0 translate-middle-y z-1"
                    style="width: 45px; height: 45px;"
                    onclick="scrollProducts(-1)">&#8249;</button>

            <!-- Product cards -->
            <div class="d-flex gap-3 overflow-hidden mx-5 justify-content-center" id="product-list">

                <!-- Product card — will repeat/generate these dynamically later -->
                <div class="card flex-shrink-0" style="width: 200px;">
                    <img src="Images/Polyterra_PLA/polyterra_PLA_Black_1_.512x512.avif" style="height: 200px; object-fit: scale-down;">
                    <div class="card-body p-2">
                        <div class="mb-1">
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">1.7/5</span>
                        </div>
                        <p class="mb-0 fw-bold small">eSUN</p>
                        <p class="text-muted small mb-2">PLA Black, 1,75 mm / 1000 g</p>
                        <p class="fw-bold mb-2">15,99 €</p>
                        <button class="btn btn-custom btn-sm w-100">Add to Cart</button>
                    </div>
                </div>

                <div class="card flex-shrink-0" style="width: 200px;">
                    <img src="Images/Elegoo_PLA_Magic/elegoo_Build_Plate_Magic_1_512x512.avif" style="height: 200px; object-fit: scale-down;">
                    <div class="card-body p-2">
                        <div class="mb-1">
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">4/5</span>
                        </div>
                        <p class="mb-0 fw-bold small">Elegoo</p>
                        <p class="text-muted small mb-2">PET/PEI Build Plate Magic, 184 × 197 mm</p>
                        <p class="fw-bold mb-2">38,99 €</p>
                        <button class="btn btn-custom btn-sm w-100">Add to Cart</button>
                    </div>
                </div>

                <div class="card flex-shrink-0" style="width: 200px;">
                    <img src="Images/Accessories/ezlok_Heat_Inserts_1.avif" style="height: 200px; object-fit: scale-down;">
                    <div class="card-body p-2">
                        <div class="mb-1">
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">4.3/5</span>
                        </div>
                        <p class="mb-0 fw-bold small">3DMajkl</p>
                        <p class="text-muted small mb-2">Heat inserts (package 50), M3 × 5,7 mm (25 ks), M4...</p>
                        <p class="fw-bold mb-2">7,99 €</p>
                        <button class="btn btn-custom btn-sm w-100">Add to Cart</button>
                    </div>
                </div>

                <div class="card flex-shrink-0" style="width: 200px;">
                    <div class="img-placeholder" style="height: 160px;">Product image</div>
                    <div class="card-body p-2">
                        <div class="mb-1">
                            <span class="badge bg-secondary" style="font-size: 0.7rem;">product rating</span>
                        </div>
                        <p class="mb-0 fw-bold small">Brand</p>
                        <p class="text-muted small mb-2">Product name</p>
                        <p class="fw-bold mb-2">Price</p>
                        <button class="btn btn-custom btn-sm w-100">Add to Cart</button>
                    </div>
                </div>

            </div>

            <!-- Next button -->
            <button class="btn btn-custom rounded-circle position-absolute top-50 end-0 translate-middle-y z-1"
                    style="width: 45px; height: 45px;"
                    onclick="scrollProducts(1)">&#8250;</button>

        </div>
    </div>
</section>
</main>
@endsection