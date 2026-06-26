@props([
    'title'       => null,
    'description' => null,
    'image'       => null,
    'canonical'   => null,
    'type'        => 'website',
    'robots'      => 'index, follow',
])

@php
    $hotelName   = config('hotel.name', 'Hotel Benizia');
    $tagline     = config('hotel.tagline', 'Luxury Hotel');
    $siteCanon   = config('hotel.canonical', 'https://hotelbenizia.ng');

    $pageTitle   = $title ? "{$title} — {$hotelName}" : "{$hotelName} — {$tagline}";
    $pageDesc    = $description ?? "Luxury hotel in Asaba, Delta State with premium rooms, restaurant, bar, swimming pool, event halls, and 24-hour hospitality services.";
    $pageImage   = $image   ?? 'https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg';
    $pageUrl     = $canonical ?? (function_exists('url') ? url()->current() : $siteCanon);

    // For OG/Twitter title we use the bare title (or hotel name) without the suffix
    $ogTitle     = $title ?? $hotelName;
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $pageUrl }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $hotelName }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:image" content="{{ $pageImage }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
<meta name="twitter:image" content="{{ $pageImage }}">
