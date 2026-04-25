@extends('layouts.shop-auth')

@section('title', 'S&J - Log In')

@section('content')
    <!-- BREADCRUMB -->
    <div class="container px-3 py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Main page</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Log in</li>
            </ol>
        </nav>
    </div>

    <!-- LOGIN FORM -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-start py-5">
        <div class="card p-4" style="width: 100%; max-width: 420px;">

            <h5 class="text-center mb-4">Log in to your Shop Account</h5>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

                <!-- Email -->
                <div class="mb-3">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control input-rounded @error('email') is-invalid @enderror"
                        placeholder="Email"
                        required
                        autofocus
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
                        autocomplete="current-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
        </div>

                <!-- Remember me -->
                <div class="form-check mb-3">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="form-check-input"
                        name="remember"
                    >
                    <label for="remember_me" class="form-check-label small text-secondary">
                        Remember me
            </label>
        </div>

                <!-- Forgot password -->
                <div class="text-center mb-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-secondary small text-decoration-none">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Log in button -->
                <div class="d-flex justify-content-center mb-3">
                    <button type="submit" id="login-submit" class="btn btn-custom px-5">
                        Log in
                    </button>
                </div>
            </form>

            <!-- Or divider ..-->
            <div class="divider small mb-3">Or</div>

            <!-- Continue with Google -->
            <div class="d-flex justify-content-center mb-2">
                <button type="button" class="btn btn-custom px-4" style="width: 220px;">
                    Continue with Google
                </button>
            </div>

            <!-- Continue with Facebook -->
            <div class="d-flex justify-content-center mb-3">
                <button type="button" class="btn btn-custom px-4" style="width: 220px;">
                    Continue with Facebook
                </button>
            </div>

            <!-- Create an account -->
            <div class="text-center">
                <a href="{{ route('register') }}" class="text-secondary small text-decoration-none">
                    Create an account
                </a>
            </div>

        </div>
    </main>
@endsection
