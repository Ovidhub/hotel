<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7C0E52">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Hotel Benizia">
    <title>Server Error — Hotel Benizia</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {{-- Vite assets (inline fallback only — avoid Vite dependency in 500) --}}
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-benizia-cream font-sans text-benizia-charcoal antialiased">

    <main class="flex min-h-screen flex-col items-center justify-center px-4 py-24 text-center">

        <div class="mb-8">
            <p class="font-serif text-[10rem] font-bold leading-none text-benizia-green/10 select-none">
                500
            </p>
        </div>

        <div class="h-1 w-16 rounded-full bg-benizia-gold mb-8 mx-auto"></div>

        <h1 class="font-serif text-4xl font-semibold text-benizia-charcoal md:text-5xl">
            Something Went Wrong
        </h1>

        <p class="mx-auto mt-5 max-w-md text-base leading-7 text-gray-500">
            We're experiencing a technical issue on our end. Our team has been notified
            and we'll have things back to normal shortly. Please try again in a moment.
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-2 rounded-full bg-benizia-green px-8 py-4 text-sm font-bold text-white shadow-md transition hover:bg-benizia-charcoal"
            >
                Back to Home
            </a>
            <a
                href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-4 text-sm font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
            >
                Contact Us
            </a>
        </div>

        <p class="mt-16 text-xs font-medium uppercase tracking-[0.3em] text-benizia-gold">
            Hotel Benizia &middot; Asaba, Delta State
        </p>

    </main>

</body>
</html>
