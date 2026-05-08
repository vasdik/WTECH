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

                <form method="POST" action="{{ route('checkout.step1.store') }}" id="checkout-step1-form" novalidate>
                    @csrf

                    <div style="max-width: 480px; margin: 0 auto;">
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    class="form-control input-rounded @error('first_name') is-invalid @enderror"
                                    placeholder="First Name"
                                    value="{{ old('first_name', $checkout['customer']['first_name']) }}"
                                    autocomplete="given-name"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="first_name_error"></div>
                                @error('first_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col">
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    class="form-control input-rounded @error('last_name') is-invalid @enderror"
                                    placeholder="Last Name"
                                    value="{{ old('last_name', $checkout['customer']['last_name']) }}"
                                    autocomplete="family-name"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="last_name_error"></div>
                                @error('last_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control input-rounded @error('email') is-invalid @enderror"
                                placeholder="Email"
                                value="{{ old('email', $checkout['customer']['email']) }}"
                                autocomplete="email"
                                required
                            >
                            <div class="invalid-feedback d-block" id="email_error"></div>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control input-rounded @error('phone') is-invalid @enderror"
                                placeholder="Phone Number"
                                value="{{ old('phone', $checkout['customer']['phone']) }}"
                                autocomplete="tel"
                                inputmode="tel"
                                required
                            >
                            <div class="invalid-feedback d-block" id="phone_error"></div>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                            <select
                                id="country"
                                name="country"
                                class="form-select input-rounded @error('country') is-invalid @enderror"
                                autocomplete="country-name"
                                required
                            >
                                <option value="">Country</option>
                                @foreach (['Slovakia', 'Czech Republic', 'Austria', 'Germany', 'Poland'] as $country)
                                    <option value="{{ $country }}" @selected(old('country', $checkout['billing_address']['country']) === $country)>{{ $country }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block" id="country_error"></div>
                            @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col">
                                <input
                                    type="text"
                                    id="street"
                                    name="street"
                                    class="form-control input-rounded @error('street') is-invalid @enderror"
                                    placeholder="Street Name"
                                    value="{{ old('street', $checkout['billing_address']['street']) }}"
                                    autocomplete="address-line1"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="street_error"></div>
                                @error('street')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-5">
                                <input
                                    type="text"
                                    id="house_number"
                                    name="house_number"
                                    class="form-control input-rounded @error('house_number') is-invalid @enderror"
                                    placeholder="House Number"
                                    value="{{ old('house_number', $checkout['billing_address']['house_number']) }}"
                                    autocomplete="address-line2"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="house_number_error"></div>
                                @error('house_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    class="form-control input-rounded @error('city') is-invalid @enderror"
                                    placeholder="City"
                                    value="{{ old('city', $checkout['billing_address']['city']) }}"
                                    autocomplete="address-level2"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="city_error"></div>
                                @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-5">
                                <input
                                    type="text"
                                    id="postal_code"
                                    name="postal_code"
                                    class="form-control input-rounded @error('postal_code') is-invalid @enderror"
                                    placeholder="Postal Code / ZIP"
                                    value="{{ old('postal_code', $checkout['billing_address']['postal_code']) }}"
                                    autocomplete="postal-code"
                                    inputmode="text"
                                    required
                                >
                                <div class="invalid-feedback d-block" id="postal_code_error"></div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkout-step1-form');
    if (!form) return;

    const fields = {
        first_name: document.getElementById('first_name'),
        last_name: document.getElementById('last_name'),
        email: document.getElementById('email'),
        phone: document.getElementById('phone'),
        country: document.getElementById('country'),
        street: document.getElementById('street'),
        house_number: document.getElementById('house_number'),
        city: document.getElementById('city'),
        postal_code: document.getElementById('postal_code'),
    };

    const postalPatterns = {
        'Slovakia': /^\d{3}\s?\d{2}$/,
        'Czech Republic': /^\d{3}\s?\d{2}$/,
        'Austria': /^\d{4}$/,
        'Germany': /^\d{5}$/,
        'Poland': /^\d{2}-\d{3}$/,
    };

    function setError(fieldName, message) {
        const field = fields[fieldName];
        const error = document.getElementById(fieldName + '_error');

        if (field) {
            field.classList.add('is-invalid');
        }

        if (error) {
            error.textContent = message;
        }
    }

    function clearError(fieldName) {
        const field = fields[fieldName];
        const error = document.getElementById(fieldName + '_error');

        if (field) {
            field.classList.remove('is-invalid');
        }

        if (error) {
            error.textContent = '';
        }
    }

    function normalizePhone(value) {
        return value.replace(/[\s\-().]/g, '');
    }

    function validateName(value) {
        return /^[A-Za-zÀ-ž\s'’-]{2,100}$/u.test(value.trim());
    }

    function validateStreet(value) {
        return /^[A-Za-zÀ-ž0-9\s.'’\/-]{2,120}$/u.test(value.trim());
    }

    function validateHouseNumber(value) {
        return /^[A-Za-z0-9\/\- ]{1,20}$/.test(value.trim());
    }

    function validateCity(value) {
        return /^[A-Za-zÀ-ž\s'’-]{2,100}$/u.test(value.trim());
    }

    function validatePhone(value) {
        const normalized = normalizePhone(value);
        return /^\+?[1-9]\d{8,14}$/.test(normalized);
    }

    function validatePostalCode(value, country) {
        const trimmed = value.trim();

        if (!country) return false;

        if (postalPatterns[country]) {
            return postalPatterns[country].test(trimmed);
        }

        return /^[A-Za-z0-9][A-Za-z0-9\- ]{2,10}$/.test(trimmed);
    }

    function validateEmail(value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
    }

    function validateField(fieldName) {
        const value = fields[fieldName].value;
        const country = fields.country.value;

        clearError(fieldName);

        switch (fieldName) {
            case 'first_name':
                if (!value.trim()) {
                    setError(fieldName, 'First name is required.');
                    return false;
                }
                if (!validateName(value)) {
                    setError(fieldName, 'Enter a valid first name.');
                    return false;
                }
                return true;

            case 'last_name':
                if (!value.trim()) {
                    setError(fieldName, 'Last name is required.');
                    return false;
                }
                if (!validateName(value)) {
                    setError(fieldName, 'Enter a valid last name.');
                    return false;
                }
                return true;

            case 'email':
                if (!value.trim()) {
                    setError(fieldName, 'Email is required.');
                    return false;
                }
                if (!validateEmail(value)) {
                    setError(fieldName, 'Enter a valid email address.');
                    return false;
                }
                return true;

            case 'phone':
                if (!value.trim()) {
                    setError(fieldName, 'Phone number is required.');
                    return false;
                }
                if (!validatePhone(value)) {
                    setError(fieldName, 'Enter a valid phone number.');
                    return false;
                }
                return true;

            case 'country':
                if (!value.trim()) {
                    setError(fieldName, 'Please select a country.');
                    return false;
                }
                return true;

            case 'street':
                if (!value.trim()) {
                    setError(fieldName, 'Street is required.');
                    return false;
                }
                if (!validateStreet(value)) {
                    setError(fieldName, 'Enter a valid street name.');
                    return false;
                }
                return true;

            case 'house_number':
                if (!value.trim()) {
                    setError(fieldName, 'House number is required.');
                    return false;
                }
                if (!validateHouseNumber(value)) {
                    setError(fieldName, 'Enter a valid house number.');
                    return false;
                }
                return true;

            case 'city':
                if (!value.trim()) {
                    setError(fieldName, 'City is required.');
                    return false;
                }
                if (!validateCity(value)) {
                    setError(fieldName, 'Enter a valid city.');
                    return false;
                }
                return true;

            case 'postal_code':
                if (!value.trim()) {
                    setError(fieldName, 'Postal code is required.');
                    return false;
                }
                if (!validatePostalCode(value, country)) {
                    let hint = 'Enter a valid postal code.';
                    if (country === 'Slovakia' || country === 'Czech Republic') {
                        hint = 'Use format 12345 or 123 45.';
                    } else if (country === 'Austria') {
                        hint = 'Use 4 digits.';
                    } else if (country === 'Germany') {
                        hint = 'Use 5 digits.';
                    } else if (country === 'Poland') {
                        hint = 'Use format 12-345.';
                    }
                    setError(fieldName, hint);
                    return false;
                }
                return true;

            default:
                return true;
        }
    }

    Object.keys(fields).forEach((fieldName) => {
        fields[fieldName].addEventListener('blur', () => validateField(fieldName));

        fields[fieldName].addEventListener('input', () => {
            if (fields[fieldName].classList.contains('is-invalid')) {
                validateField(fieldName);
            }
        });
    });

    fields.country.addEventListener('change', () => {
        validateField('country');
        if (fields.postal_code.value.trim()) {
            validateField('postal_code');
        }
    });

    form.addEventListener('submit', (e) => {
        let isValid = true;

        Object.keys(fields).forEach((fieldName) => {
            const fieldValid = validateField(fieldName);
            if (!fieldValid) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();

            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });
});
</script>
@endpush