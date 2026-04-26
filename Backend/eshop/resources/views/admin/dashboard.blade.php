@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Panel</li>
        </ol>
    </nav>
@endsection

@section('content')

@include('admin.partials.admin-nav-buttons', ['mode' => 'dashboard'])

    <h2 class="fw-bold mb-4">Admin Panel</h2>

    <p>
        <h5>
        Welcome to the admin dashboard. 
        </h5>

        Use the navigation above to manage products and other settings.
    </p>

    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-custom btn-sm">Log out</button>
        </form>
    </div>
@endsection