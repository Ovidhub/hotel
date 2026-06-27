<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1D5C52">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Hotel Benizia">
    <title>Page Not Found — Hotel Benizia</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {{-- Vite assets --}}
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-benizia-cream font-sans text-benizia-charcoal antialiased">

    {{-- Header --}}
    @include('partials.header')

    <main class="flex min-h-screen flex-col items-center justify-center px-4 py-24 text-center">

        {{-- 404 visual --}}
        <div class="mb-8">
            <p class="font-serif text-[10rem] font-bold leading-none text-benizia-green/10 select-none">
                404
            </p>
        </div>

        {{-- Gold accent --}}
        <div class="h-1 w-16 rounded-full bg-benizia-gold mb-8 mx-auto"></div>

        {{-- Heading --}}
        <h1 class="font-serif text-4xl font-semibold text-benizia-charcoal md:text-5xl">
            Page Not Found
        </h1>

        {{-- Subtext --}}
        <p class="mx-auto mt-5 max-w-md text-base leading-7 text-gray-500">
            It looks like you've wandered off the beaten path. The page you're looking for doesn't exist
            or may have been moved. Let us guide you back.
        </p>

        {{-- Navigation links --}}
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-2 rounded-full bg-benizia-green px-8 py-4 text-sm font-bold text-white shadow-md transition hover:bg-benizia-charcoal"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Back to Home
            </a>
            <a
                href="{{ route('rooms.index') }}"
                class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-4 text-sm font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
            >
                Browse Rooms
            </a>
            <a
                href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-gold px-8 py-4 text-sm font-bold text-benizia-gold transition hover:bg-benizia-gold hover:text-white"
            >
                Contact Us
            </a>
        </div>

        {{-- Hotel tagline --}}
        <p class="mt-16 text-xs font-medium uppercase tracking-[0.3em] text-benizia-gold">
            Hotel Benizia &middot; Asaba, Delta State
        </p>

    </main>

    {{-- Footer --}}
    @include('partials.footer')

</body>
</html>
