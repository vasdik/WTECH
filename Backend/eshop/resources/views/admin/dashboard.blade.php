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
    <h2 class="mb-4">Admin Panel</h2>

    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-custom btn-sm">Add</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-custom btn-sm">Edit</a>

        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-custom btn-sm">Log out</button>
        </form>
    </div>
@endsection