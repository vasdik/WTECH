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
    @php
        $adminSteps = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['key' => 'products', 'label' => 'Products', 'route' => route('admin.products.index')],
            ['key' => 'create', 'label' => 'Add Product', 'route' => route('admin.products.create')],
        ];
    @endphp

    @include('admin.partials.admin-nav-bar', [
        'steps' => $adminSteps,
        'currentStep' => 'create',
    ])

    <h2 class="fw-bold mb-4">Admin Panel</h2>

    <div class="mx-auto" style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.products.partials.form')

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom">Add</button>
            </div>
        </form>
    </div>
@endsection