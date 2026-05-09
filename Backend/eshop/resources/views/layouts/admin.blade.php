<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">

    @include('admin.partials.admin-header')

    @include('admin.partials.admin-category-nav')

    @hasSection('breadcrumb')
        <div class="container px-3 py-2">
            @yield('breadcrumb')
        </div>
    @endif

    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            @if (session('cart_success'))
                <div class="alert alert-success">
                    {{ session('cart_success') }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @include('admin.partials.admin-footer')

    @stack('scripts')
</body>
</html>