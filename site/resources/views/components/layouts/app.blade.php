@props([
    'title'       => null,
    'description' => null,
    'canonical'   => null,
    'image'       => null,
    'ogType'      => 'website',
])
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1D5C52">

    {{-- Google Fonts: preconnect then load Playfair Display + Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {{-- SEO meta tags --}}
    <x-seo
        :title="$title"
        :description="$description"
        :canonical="$canonical"
        :image="$image"
        :type="$ogType"
    />

    {{-- Site-wide Hotel structured data --}}
    <x-schema.hotel />

    {{-- Vite assets (Tailwind CSS + Alpine.js) --}}
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-benizia-cream font-sans text-benizia-charcoal antialiased">

    {{-- Sticky header --}}
    @include('partials.header')

    {{-- Amenity ticker ribbon --}}
    @include('partials.amenity-ticker')

    {{-- Main page content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('partials.footer')

</body>
</html>
