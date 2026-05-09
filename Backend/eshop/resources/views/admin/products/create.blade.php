@extends('layouts.admin')

@section('title', 'Admin - Add Product')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('admin.partials.admin-nav-buttons', ['mode' => 'create'])

    <h2 class="fw-bold mb-4">Admin Panel</h2>

    <div class="mx-auto" style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="admin-product-create-form" novalidate>
            @csrf

            @include('admin.products.partials.form')

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom">Add</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('admin-product-create-form');
    const imagesInput = document.getElementById('product-images');
    const imagesError = document.getElementById('images_client_error');

    const nameInput = document.getElementById('product-name');
    const nameError = document.getElementById('name_client_error');

    const descriptionInput = document.getElementById('product-description');
    const descriptionError = document.getElementById('description_client_error');

    if (!form || !imagesInput || !imagesError || !nameInput || !nameError || !descriptionInput || !descriptionError) {
        return;
    }

    function showError(input, errorBox, message) {
        input.classList.add('is-invalid');
        errorBox.textContent = message;
    }

    function clearError(input, errorBox) {
        input.classList.remove('is-invalid');
        errorBox.textContent = '';
    }

    function validateImages() {
        const fileCount = imagesInput.files ? imagesInput.files.length : 0;

        if (fileCount < 2) {
            showError(imagesInput, imagesError, 'Add at least two product photos.');
            return false;
        }

        clearError(imagesInput, imagesError);
        return true;
    }

    function validateName() {
        const value = nameInput.value.trim();

        if (value.length === 0) {
            showError(nameInput, nameError, 'Product name is required.');
            return false;
        }

        clearError(nameInput, nameError);
        return true;
    }

    function validateDescription() {
        const value = descriptionInput.value.trim();

        if (value.length === 0) {
            showError(descriptionInput, descriptionError, 'Description is required.');
            return false;
        }

        clearError(descriptionInput, descriptionError);
        return true;
    }

    imagesInput.addEventListener('change', validateImages);

    nameInput.addEventListener('input', () => {
        if (nameInput.classList.contains('is-invalid')) {
            validateName();
        }
    });

    nameInput.addEventListener('blur', validateName);

    descriptionInput.addEventListener('input', () => {
        if (descriptionInput.classList.contains('is-invalid')) {
            validateDescription();
        }
    });

    descriptionInput.addEventListener('blur', validateDescription);

    form.addEventListener('submit', (e) => {
        const imagesValid = validateImages();
        const nameValid = validateName();
        const descriptionValid = validateDescription();

        if (!imagesValid || !nameValid || !descriptionValid) {
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