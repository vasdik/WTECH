@extends('layouts.shop')

@section('title', 'S&J - Checkout: My Information')

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
                    <a href="{{ route('cart') }}" class="btn btn-custom btn-sm">Back</a>
                    <h5 class="mb-0">My information</h5>
                    <div style="width: 72px;"></div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" style="max-width: 480px; margin: 0 auto 1rem;">
                        Please fix the highlighted fields.
                    </div>
                @endif

                <form method="POST" action="{{ route('checkout.step1.store') }}">
                    @csrf

                    <div style="max-width: 480px; margin: 0 auto;">
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <input type="text" name="first_name" class="form-control input-rounded @error('first_name') is-invalid @enderror" placeholder="First Name" value="{{ old('first_name', $checkout['customer']['first_name']) }}" required>
                                @error('first_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col">
                                <input type="text" name="last_name" class="form-control input-rounded @error('last_name') is-invalid @enderror" placeholder="Last Name" value="{{ old('last_name', $checkout['customer']['last_name']) }}" required>
                                @error('last_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <input type="email" name="email" class="form-control input-rounded @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', $checkout['customer']['email']) }}" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                            <input type="tel" name="phone" class="form-control input-rounded @error('phone') is-invalid @enderror" placeholder="Phone Number" value="{{ old('phone', $checkout['customer']['phone']) }}" required>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                            <select name="country" class="form-select input-rounded @error('country') is-invalid @enderror" required>
                                <option value="">Country</option>
                                @foreach (['Slovakia', 'Czech Republic', 'Austria', 'Germany', 'Poland'] as $country)
                                    <option value="{{ $country }}" @selected(old('country', $checkout['billing_address']['country']) === $country)>{{ $country }}</option>
                                @endforeach
                            </select>
                            @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col">
                                <input type="text" name="street" class="form-control input-rounded @error('street') is-invalid @enderror" placeholder="Street Name" value="{{ old('street', $checkout['billing_address']['street']) }}" required>
                                @error('street')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-5">
                                <input type="text" name="house_number" class="form-control input-rounded @error('house_number') is-invalid @enderror" placeholder="House Number" value="{{ old('house_number', $checkout['billing_address']['house_number']) }}" required>
                                @error('house_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <input type="text" name="city" class="form-control input-rounded @error('city') is-invalid @enderror" placeholder="City" value="{{ old('city', $checkout['billing_address']['city']) }}" required>
                                @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-5">
                                <input type="text" name="postal_code" class="form-control input-rounded @error('postal_code') is-invalid @enderror" placeholder="Postal Code / ZIP" value="{{ old('postal_code', $checkout['billing_address']['postal_code']) }}" required>
                                @error('postal_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @guest
                            <div class="d-flex justify-content-center mb-2">
                                <a href="{{ route('register') }}" class="btn btn-custom btn-sm">Create an Account</a>
                            </div>
                            <p class="text-center small text-muted">
                                Already registered? <a href="{{ route('login') }}" class="text-secondary">Log in.</a>
                            </p>
                        @endguest
                    </div>

                    <div style="max-width: 480px; margin: 1.5rem auto 0;">
                        <button type="submit" class="btn btn-custom w-100">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection