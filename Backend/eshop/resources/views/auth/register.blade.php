@extends('layouts.shop-auth')

@section('title', 'S&J - Register')

@section('content')
    <!-- BREADCRUMB -->
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Main page</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Register</li>
            </ol>
        </nav>
    </div>

    <!-- REGISTER FORM -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-start py-5">
        <div class="card p-4" style="width: 100%; max-width: 480px;">

            <h5 class="text-center mb-4">Create your Shop Account</h5>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-2">
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control input-rounded @error('name') is-invalid @enderror"
                        placeholder="Full Name"
                        required
                        autofocus
                        autocomplete="name"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-2">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control input-rounded @error('email') is-invalid @enderror"
                        placeholder="Email"
                        required
                        autocomplete="username"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <input
                        type="password"
                        name="password"
                        class="form-control input-rounded @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required
                        autocomplete="new-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control input-rounded @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm Password"
                        required
                        autocomplete="new-password"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Create an Account button -->
                <div class="d-flex justify-content-center mb-3">
                    <button type="submit" id="register-submit" class="btn btn-custom px-4">
                        Create an Account
                    </button>
                </div>

                <!-- Already registered? -->
                <div class="text-center">
                    <small class="text-secondary">
                        Already registered?
                        <a href="{{ route('login') }}" class="text-secondary">Log in.</a>
                    </small>
                </div>
            </form>

        </div>
    </main>
@endsection