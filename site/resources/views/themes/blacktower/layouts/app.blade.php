{{-- resources/views/themes/blacktower/layouts/app.blade.php --}}
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('hotel.name'))</title>
    <meta name="description" content="@yield('description', 'Premium comfort and elegant rooms at '.config('hotel.name').'.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/themes/blacktower/favicon.png') }}">
    <x-schema.hotel />
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/blacktower.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="site-shell">
        @include('themes.blacktower.partials.header')
        @yield('content')
        @include('themes.blacktower.partials.footer')
    </div>
</body>
</html>
