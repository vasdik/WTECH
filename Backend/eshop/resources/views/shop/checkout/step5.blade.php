@extends('layouts.shop')

@section('title', 'S&J - Checkout: Summary')

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart') }}">My Cart</a></li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        @include('shop.checkout.partials.stepper', ['currentStep' => $currentStep])

        <div class="container px-3 pb-5">
            <div class="border rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('checkout.step4') }}" class="btn btn-custom btn-sm">Back</a>
                </div>

                <div class="row g-4">
                    <div class="col-md-8">
                        <h5 class="text-center mb-4">Summary</h5>

                        <div class="address-card">
                            <div class="row align-items-center">
                                <div class="col-3 text-muted small">Delivery Address:</div>
                                <div class="col">
                                    <p class="fw-bold mb-0">{{ $checkout['customer']['first_name'] }} {{ $checkout['customer']['last_name'] }}</p>
                                    <p class="small text-muted mb-0">
                                        {{ $checkout['delivery_address']['street'] }} {{ $checkout['delivery_address']['house_number'] }}<br>
                                        {{ $checkout['delivery_address']['postal_code'] }} {{ $checkout['delivery_address']['city'] }}, {{ $checkout['delivery_address']['country'] }}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('checkout.step2') }}" class="btn btn-custom btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>

                        <div class="address-card">
                            <div class="row align-items-center">
                                <div class="col-3 text-muted small">Billing Address:</div>
                                <div class="col">
                                    <p class="fw-bold mb-0">{{ $checkout['customer']['first_name'] }} {{ $checkout['customer']['last_name'] }}</p>
                                    <p class="small text-muted mb-0">
                                        {{ $checkout['billing_address']['street'] }} {{ $checkout['billing_address']['house_number'] }}<br>
                                        {{ $checkout['billing_address']['postal_code'] }} {{ $checkout['billing_address']['city'] }}, {{ $checkout['billing_address']['country'] }}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('checkout.step2') }}" class="btn btn-custom btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>

                        <div class="address-card">
                            <div class="row align-items-center">
                                <div class="col-3 text-muted small">Payment Method:</div>
                                <div class="col">
                                    <p class="mb-0">
                                        {{ collect(app(\App\Services\CheckoutService::class)->paymentMethods())->firstWhere('code', $checkout['payment']['code'])['label'] ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('checkout.step3') }}" class="btn btn-custom btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>

                        <div class="address-card">
                            <div class="row align-items-center">
                                <div class="col-3 text-muted small">Delivery Method:</div>
                                <div class="col">
                                    <p class="mb-0">{{ $checkout['shipping']['label'] ?? '-' }}</p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('checkout.step4') }}" class="btn btn-custom btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>

                        @if ($checkout['shipping']['eta_label'])
                            <p class="small mt-2">{{ $checkout['shipping']['eta_label'] }}</p>
                        @endif

                        <hr class="my-4">

                        @foreach ($summary['items'] as $item)
                            @php
                                $product = $item['product'];
                                $image = $item['primary_image'];
                            @endphp

                            <div class="row g-3 align-items-center border-bottom pb-4 mb-4">
                                <div class="col-auto">
                                    @if ($image)
                                        <img
                                            src="{{ asset($image->path) }}"
                                            alt="{{ $image->alt_text ?? $product->name }}"
                                            style="width: 120px; height: 120px; object-fit: scale-down;"
                                        >
                                    @else
                                        <div class="img-placeholder" style="width: 120px; height: 120px;">Product image</div>
                                    @endif
                                </div>

                                <div class="col">
                                    <p class="fw-bold mb-0">{{ $product->brand }}</p>
                                    <p class="text-muted small mb-1">{{ $product->name }}</p>
                                    <p class="text-success small fw-bold mb-1">{{ $product->stock_qty > 0 ? 'In stock' : 'Out of stock' }}</p>
                                    <p class="mb-0 fw-bold">{{ number_format($item['price_gross'], 2) }} €</p>
                                    <p class="text-muted small mb-0">before VAT: {{ number_format($item['line_net'] / max($item['quantity'], 1), 2) }} €</p>
                                </div>

                                <div class="col-auto text-end">
                                    <p class="text-muted small mb-1">In cart: {{ $item['quantity'] }}</p>
                                    <p class="fw-bold">Sum: {{ number_format($item['line_total'], 2) }} €</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-bold mb-3">Cart overview:</h6>

                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Subtotal:</span><span>{{ number_format($summary['subtotal'], 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Savings on discounts:</span><span>{{ number_format($summary['discount'], 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Subtotal after discounts:</span><span>{{ number_format($summary['subtotal_after_discount'], 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-muted">Packaging and Shipping:</span><span>{{ number_format($summary['shipping'], 2) }} €</span>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <input type="text" class="form-control form-control-sm" placeholder="Discount Coupon">
                            <button type="button" class="btn btn-custom btn-sm">Apply</button>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold small">Total amount:</span>
                            <span class="fw-bold">{{ number_format($summary['total'], 2) }} €</span>
                        </div>
                        <p class="text-muted small mb-3">Includes 23% VAT: {{ number_format($summary['vat_total'], 2) }} €</p>

                        <form method="POST" action="{{ route('checkout.complete') }}">
                            @csrf
                            <button class="btn btn-custom w-100">Complete Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection