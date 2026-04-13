<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'S&J')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite('resources/css/app.css')
    </head>
    <body class="d-flex flex-column min-vh-100">

        @include('shop.partials.shop.header')
        @include('shop.partials.shop.category-nav')

        <main class="flex-grow-1 py-5">
            @yield('content')
        </main>

        @include('shop.partials.shop.footer')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>