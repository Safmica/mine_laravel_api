<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/mine_icon.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    @if (!Request::is('login') && !Request::is('signup'))
        <x-navbar />
    @endif
    <x-alert />
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>
</body>
<script src="//unpkg.com/alpinejs" defer></script>
</html>