@extends('layouts.shop')

@section('title', 'S&J - Checkout: Payment Method')

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
                    <a href="{{ route('checkout.step2') }}" class="btn btn-custom btn-sm">Back</a>
                    <h5 class="mb-0">Payment Method</h5>
                    <div style="width: 72px;"></div>
                </div>

                <form method="POST" action="{{ route('checkout.step3.store') }}">
                    @csrf

                    <div style="max-width: 480px; margin: 0 auto;">
                        @foreach ($paymentMethods as $method)
                            <label class="delivery-option mb-2">
                                <span>{{ $method['label'] }}</span>
                                <input
                                    type="radio"
                                    name="payment_code"
                                    value="{{ $method['code'] }}"
                                    class="form-check-input"
                                    {{ old('payment_code', $checkout['payment']['code']) === $method['code'] ? 'checked' : '' }}
                                >
                            </label>
                        @endforeach

                        <div class="d-flex gap-2 mb-4 mt-3">
                            <input type="text" class="form-control" placeholder="Discount Code">
                            <button type="button" class="btn btn-custom">Apply</button>
                        </div>

                        <button type="submit" class="btn btn-custom w-100">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection