@extends('layouts.shop')

@section('title', 'S&J - Order Success')

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
                <li class="breadcrumb-item active">Order Success</li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1">
        <div class="container px-3 py-5">
            <div class="border rounded p-4 text-center" style="max-width: 720px; margin: 0 auto;">
                <h3 class="fw-bold mb-3">Thank you for your order</h3>
                <p class="mb-1">Your order was created successfully.</p>
                <p class="mb-3">
                    Order number:
                    <span class="fw-bold">{{ $order->order_number }}</span>
                </p>

                <div class="mb-4">
                    <p class="mb-1">Total amount: <span class="fw-bold">{{ number_format((float) $order->total_amount, 2) }} €</span></p>
                    <p class="text-muted small mb-0">Payment: {{ $order->payment_label }}</p>
                    <p class="text-muted small">Shipping: {{ $order->shipping_label }}</p>
                </div>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-custom">Continue shopping</a>
                    <a href="{{ route('cart') }}" class="btn btn-outline-custom">Back to cart</a>
                </div>
            </div>
        </div>
    </main>
@endsection