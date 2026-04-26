@extends('layouts.admin')

@section('title', 'Admin - Edit Product')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('admin.partials.admin-nav-buttons', [
    'mode' => 'edit',
    'product' => $product,
])

    <h2 class="fw-bold mb-4">Admin Panel</h2>

    <div class="mx-auto" style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.products.partials.form')

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom">Save Edits</button>
            </div>
        </form>

        <div class="mb-4 mt-4">
            <p class="small text-muted mb-2">Uploaded:</p>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @forelse ($product->images as $image)
                    <div class="uploaded-thumb">
                        <img
                            src="{{ asset($image->path) }}"
                            alt="{{ $image->alt_text ?? $product->name }}"
                            style="width:70px;height:70px;object-fit:cover;"
                        >

                        <form
                            method="POST"
                            action="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                            onsubmit="return confirm('Delete this image?')"
                            class="m-0"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn">&#x2715;</button>
                        </form>
                    </div>
                @empty
                    <div class="uploaded-thumb">
                        <div class="img-placeholder" style="width:70px;height:70px;">img</div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center mt-4">
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-custom">Remove</button>
            </form>
        </div>
    </div>
@endsection