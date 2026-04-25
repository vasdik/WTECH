@extends('layouts.shop')

@section('title', 'Admin Dashboard')

@section('content')
    <main class="flex-grow-1">
        <div class="container py-4">
            <h2 class="mb-4">Admin Panel</h2>

            <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                <a href="{{ route('admin.products.create') }}" class="btn btn-custom btn-sm">Add</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-custom btn-sm">Edit</a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-custom btn-sm">Log out</button>
                </form>
            </div>
        </div>
    </main>
@endsection