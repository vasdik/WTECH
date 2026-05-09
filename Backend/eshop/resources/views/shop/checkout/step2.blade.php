@extends('layouts.shop')

@section('title', 'S&J - Checkout: Delivery Address')

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
                    <a href="{{ route('checkout.step1') }}" class="btn btn-custom btn-sm">Back</a>
                    <h5 class="mb-0">Delivery Address</h5>
                    <div style="width: 72px;"></div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" style="max-width: 640px; margin: 0 auto 1rem;">
                        Please fix the highlighted fields.
                    </div>
                @endif

                <form method="POST" action="{{ route('checkout.step2.store') }}" id="checkout-step2-form" novalidate>
                    @csrf

                    <div style="max-width: 640px; margin: 0 auto;">
                        <h6 class="fw-bold mb-3">Billing address</h6>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="billing_source" id="billing_entered" value="entered" {{ old('billing_source', 'entered') === 'entered' ? 'checked' : '' }}>
                            <label class="form-check-label" for="billing_entered">
                                Use address entered in Step 1
                            </label>
                        </div>

                        <div class="address-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold">{{ $checkout['customer']['first_name'] }} {{ $checkout['customer']['last_name'] }}</span>
                                <span class="badge bg-light text-dark border">Entered billing address</span>
                            </div>
                            <p class="small text-muted mb-0">
                                {{ $checkout['billing_address']['street'] }} {{ $checkout['billing_address']['house_number'] }}<br>
                                {{ $checkout['billing_address']['postal_code'] }} {{ $checkout['billing_address']['city'] }}, {{ $checkout['billing_address']['country'] }}
                            </p>
                        </div>

                        @auth
                            @if ($savedAddresses->isNotEmpty())
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="billing_source" id="billing_saved" value="saved" {{ old('billing_source') === 'saved' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="billing_saved">
                                        Use saved billing address
                                    </label>
                                </div>

                                @foreach ($savedAddresses as $address)
                                    <label class="address-card d-block mb-2" style="cursor:pointer;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <input type="radio" name="saved_billing_address_id" value="{{ $address->id }}" class="form-check-input me-2" {{ (string) old('saved_billing_address_id') === (string) $address->id ? 'checked' : '' }}>
                                                <span class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark border">
                                                {{ $address->label ?: 'Saved address' }}
                                            </span>
                                        </div>
                                        <p class="small text-muted mb-0">
                                            {{ $address->street }} {{ $address->house_number }}<br>
                                            {{ $address->postal_code }} {{ $address->city }}, {{ $address->country }}
                                        </p>
                                    </label>
                                @endforeach

                                @error('saved_billing_address_id')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                            @endif
                        @endauth

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Delivery address</h6>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="delivery_mode" id="same_as_billing" value="same_as_billing" {{ old('delivery_mode', $checkout['delivery_address']['mode']) === 'same_as_billing' ? 'checked' : '' }}>
                            <label class="form-check-label" for="same_as_billing">
                                Use billing address as delivery address
                            </label>
                        </div>

                        @auth
                            @if ($savedAddresses->isNotEmpty())
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="delivery_mode" id="delivery_saved" value="saved" {{ old('delivery_mode', $checkout['delivery_address']['mode']) === 'saved' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delivery_saved">
                                        Use saved delivery address
                                    </label>
                                </div>

                                @foreach ($savedAddresses as $address)
                                    <label class="address-card d-block mb-2" style="cursor:pointer;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <input type="radio" name="saved_delivery_address_id" value="{{ $address->id }}" class="form-check-input me-2" {{ (string) old('saved_delivery_address_id') === (string) $address->id ? 'checked' : '' }}>
                                                <span class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark border">
                                                {{ $address->label ?: 'Saved address' }}
                                            </span>
                                        </div>
                                        <p class="small text-muted mb-0">
                                            {{ $address->street }} {{ $address->house_number }}<br>
                                            {{ $address->postal_code }} {{ $address->city }}, {{ $address->country }}
                                        </p>
                                    </label>
                                @endforeach

                                @error('saved_delivery_address_id')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                            @endif
                        @endauth

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="delivery_mode" id="custom_delivery" value="custom" {{ old('delivery_mode', $checkout['delivery_address']['mode']) === 'custom' ? 'checked' : '' }}>
                            <label class="form-check-label" for="custom_delivery">
                                Use different custom delivery address
                            </label>
                        </div>

                        <div class="border rounded p-3">
                            <div class="mb-2">
                                <select
                                    id="delivery_country"
                                    name="delivery_country"
                                    class="form-select input-rounded @error('delivery_country') is-invalid @enderror"
                                >
                                    <option value="">Country</option>
                                    @foreach (['Slovakia', 'Czech Republic', 'Austria', 'Germany', 'Poland'] as $country)
                                        <option value="{{ $country }}" @selected(old('delivery_country', $checkout['delivery_address']['country']) === $country)>{{ $country }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block" id="delivery_country_error"></div>
                                @error('delivery_country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input
                                        type="text"
                                        id="delivery_street"
                                        name="delivery_street"
                                        class="form-control input-rounded @error('delivery_street') is-invalid @enderror"
                                        placeholder="Street Name"
                                        value="{{ old('delivery_street', $checkout['delivery_address']['street']) }}"
                                    >
                                    <div class="invalid-feedback d-block" id="delivery_street_error"></div>
                                    @error('delivery_street')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-5">
                                    <input
                                        type="text"
                                        id="delivery_house_number"
                                        name="delivery_house_number"
                                        class="form-control input-rounded @error('delivery_house_number') is-invalid @enderror"
                                        placeholder="House Number"
                                        value="{{ old('delivery_house_number', $checkout['delivery_address']['house_number']) }}"
                                    >
                                    <div class="invalid-feedback d-block" id="delivery_house_number_error"></div>
                                    @error('delivery_house_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input
                                        type="text"
                                        id="delivery_city"
                                        name="delivery_city"
                                        class="form-control input-rounded @error('delivery_city') is-invalid @enderror"
                                        placeholder="City"
                                        value="{{ old('delivery_city', $checkout['delivery_address']['city']) }}"
                                    >
                                    <div class="invalid-feedback d-block" id="delivery_city_error"></div>
                                    @error('delivery_city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-5">
                                    <input
                                        type="text"
                                        id="delivery_postal_code"
                                        name="delivery_postal_code"
                                        class="form-control input-rounded @error('delivery_postal_code') is-invalid @enderror"
                                        placeholder="Postal Code / ZIP"
                                        value="{{ old('delivery_postal_code', $checkout['delivery_address']['postal_code']) }}"
                                    >
                                    <div class="invalid-feedback d-block" id="delivery_postal_code_error"></div>
                                    @error('delivery_postal_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-custom w-100 mt-4">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkout-step2-form');
    if (!form) return;

    const deliveryModeInputs = Array.from(document.querySelectorAll('input[name="delivery_mode"]'));

    const fields = {
        delivery_country: document.getElementById('delivery_country'),
        delivery_street: document.getElementById('delivery_street'),
        delivery_house_number: document.getElementById('delivery_house_number'),
        delivery_city: document.getElementById('delivery_city'),
        delivery_postal_code: document.getElementById('delivery_postal_code'),
    };

    const postalPatterns = {
        'Slovakia': /^\d{3}\s?\d{2}$/,
        'Czech Republic': /^\d{3}\s?\d{2}$/,
        'Austria': /^\d{4}$/,
        'Germany': /^\d{5}$/,
        'Poland': /^\d{2}-\d{3}$/,
    };

    function getSelectedDeliveryMode() {
        const checked = deliveryModeInputs.find(input => input.checked);
        return checked ? checked.value : null;
    }

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

    function clearAllCustomErrors() {
        Object.keys(fields).forEach(clearError);
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

    function validatePostalCode(value, country) {
        const trimmed = value.trim();

        if (!country) return false;

        if (postalPatterns[country]) {
            return postalPatterns[country].test(trimmed);
        }

        return /^[A-Za-z0-9][A-Za-z0-9\- ]{2,10}$/.test(trimmed);
    }

    function validateCustomField(fieldName) {
        const value = fields[fieldName].value;
        const country = fields.delivery_country.value;

        clearError(fieldName);

        switch (fieldName) {
            case 'delivery_country':
                if (!value.trim()) {
                    setError(fieldName, 'Please select a country.');
                    return false;
                }
                return true;

            case 'delivery_street':
                if (!value.trim()) {
                    setError(fieldName, 'Street is required.');
                    return false;
                }
                if (!validateStreet(value)) {
                    setError(fieldName, 'Enter a valid street name.');
                    return false;
                }
                return true;

            case 'delivery_house_number':
                if (!value.trim()) {
                    setError(fieldName, 'House number is required.');
                    return false;
                }
                if (!validateHouseNumber(value)) {
                    setError(fieldName, 'Enter a valid house number.');
                    return false;
                }
                return true;

            case 'delivery_city':
                if (!value.trim()) {
                    setError(fieldName, 'City is required.');
                    return false;
                }
                if (!validateCity(value)) {
                    setError(fieldName, 'Enter a valid city.');
                    return false;
                }
                return true;

            case 'delivery_postal_code':
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

    function toggleCustomRequirements() {
        const isCustom = getSelectedDeliveryMode() === 'custom';

        Object.values(fields).forEach(field => {
            if (!field) return;
            field.required = isCustom;
        });

        if (!isCustom) {
            clearAllCustomErrors();
        }
    }

    Object.keys(fields).forEach((fieldName) => {
        fields[fieldName].addEventListener('blur', () => {
            if (getSelectedDeliveryMode() === 'custom') {
                validateCustomField(fieldName);
            }
        });

        fields[fieldName].addEventListener('input', () => {
            if (getSelectedDeliveryMode() === 'custom' && fields[fieldName].classList.contains('is-invalid')) {
                validateCustomField(fieldName);
            }
        });
    });

    fields.delivery_country.addEventListener('change', () => {
        if (getSelectedDeliveryMode() === 'custom') {
            validateCustomField('delivery_country');

            if (fields.delivery_postal_code.value.trim()) {
                validateCustomField('delivery_postal_code');
            }
        }
    });

    deliveryModeInputs.forEach(input => {
        input.addEventListener('change', () => {
            toggleCustomRequirements();
        });
    });

    form.addEventListener('submit', (e) => {
        if (getSelectedDeliveryMode() !== 'custom') {
            clearAllCustomErrors();
            return;
        }

        let isValid = true;

        Object.keys(fields).forEach((fieldName) => {
            const fieldValid = validateCustomField(fieldName);
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

    toggleCustomRequirements();
});
</script>
@endpush