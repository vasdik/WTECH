@extends('layouts.shop')

@section('title', 'Admin - Add Product')

@section('content')
    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <h2 class="fw-bold mb-4">Admin Panel</h2>

            <div class="mx-auto" style="max-width: 700px;">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('admin.products.form')

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection