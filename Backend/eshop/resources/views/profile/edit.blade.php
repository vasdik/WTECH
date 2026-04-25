@extends('layouts.shop-auth')

@section('title', 'Profile')

@section('content')
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Main page</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>

    <main class="flex-grow-1 py-5">
        <div class="container" style="max-width: 700px;">

            <div class="d-flex justify-content-end mb-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-custom">
                        Log out
                    </button>
                </form>
            </div>

            <div class="card p-4 mb-4">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="card p-4 mb-4">
                @include('profile.partials.update-password-form')
            </div>

            <div class="card p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </main>
@endsection