@props([
    'items' => null,
])

@php
    // Default to first 8 ticker items from config if none provided
    $amenities = $items ?? array_slice(config('hotel.ticker', []), 0, 8);
@endphp

<div class="flex flex-wrap justify-center gap-3" aria-label="Hotel amenities">
    @foreach($amenities as $amenity)
        <span class="inline-flex items-center gap-2 rounded-full border border-benizia-gold/40 bg-benizia-cream px-4 py-2 text-sm font-medium text-benizia-green shadow-sm transition hover:bg-benizia-green hover:text-white hover:border-transparent">
            {{-- Gold dot accent --}}
            <span class="h-1.5 w-1.5 rounded-full bg-benizia-gold" aria-hidden="true"></span>
            {{ $amenity }}
        </span>
    @endforeach
</div>
