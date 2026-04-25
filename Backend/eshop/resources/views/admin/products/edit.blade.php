@extends('layouts.shop')

@section('title', 'Admin - Edit Product')

@section('content')
    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <h2 class="fw-bold mb-4">Admin Panel</h2>

            <div class="mx-auto" style="max-width: 700px;">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @include('admin.products.form')

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Save Edits</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-custom">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection