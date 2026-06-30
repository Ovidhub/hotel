@props([
    'room',
])

@php
    $detailUrl  = \Illuminate\Support\Facades\Route::has('rooms.show')
        ? route('rooms.show', $room)
        : '#';
    $bookingUrl = \Illuminate\Support\Facades\Route::has('booking.create')
        ? route('booking.create', ['type' => 'room', 'slug' => $room->slug])
        : '#';
    $image      = (method_exists($room, 'imageUrl') ? $room->imageUrl() : $room->image) ?? 'https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg?auto=compress&cs=tinysrgb&w=800';
    $name       = $room->name ?? 'Room';
    $category   = $room->category ?? 'Standard';
    $rating     = $room->rating ?? 5;
    $excerpt    = $room->excerpt ?? $room->description ?? '';
    $price      = method_exists($room, 'getAttribute') ? $room->price_formatted : null;
    $beds       = $room->beds ?? null;
    $guests     = $room->guests ?? null;
    $size       = $room->size ?? null;
@endphp

<article class="group overflow-hidden rounded-3xl bg-white ring-1 ring-gray-100 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(29,92,82,0.12)]">

    {{-- Image --}}
    <a href="{{ $detailUrl }}" class="relative block h-64 overflow-hidden" aria-label="View {{ $name }} details">
        <img
            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
            src="{{ $image }}"
            alt="{{ $name }} at Hotel Benizia"
            loading="lazy"
        >
        {{-- Category badge --}}
        <span class="absolute left-4 top-4 rounded-full bg-benizia-green px-3 py-1.5 text-xs font-semibold text-white shadow">
            {{ $category }}
        </span>
    </a>

    {{-- Card body --}}
    <div class="p-6">

        {{-- Price --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-benizia-gold">from</p>
                <p class="font-serif mt-0.5 text-2xl text-benizia-charcoal">
                    {{ $price ?? 'Contact us' }} <span class="text-base font-normal text-gray-400">/ night</span>
                </p>
            </div>
        </div>

        {{-- Room name --}}
        <h3 class="font-serif mt-4 text-xl text-benizia-charcoal">{{ $name }}</h3>

        {{-- Excerpt --}}
        @if($excerpt)
            <p class="mt-2 text-sm leading-6 text-gray-500 line-clamp-2">{{ $excerpt }}</p>
        @endif

        {{-- Specs chips --}}
        @if($beds || $guests || $size)
            <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-500">
                @if($guests)
                    <span class="flex items-center gap-1 rounded-full bg-benizia-cream px-3 py-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        {{ $guests }}
                    </span>
                @endif
                @if($beds)
                    <span class="flex items-center gap-1 rounded-full bg-benizia-cream px-3 py-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        {{ $beds }}
                    </span>
                @endif
                @if($size)
                    <span class="flex items-center gap-1 rounded-full bg-benizia-cream px-3 py-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                        {{ $size }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="{{ $bookingUrl }}"
               class="flex-1 rounded-full bg-benizia-green py-3 text-center text-sm font-bold text-white transition hover:bg-benizia-charcoal">
                Book Room
            </a>
            <a href="{{ $detailUrl }}"
               class="flex-1 rounded-full border border-benizia-gold py-3 text-center text-sm font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white hover:border-transparent">
                View Details
            </a>
        </div>
    </div>
</article>
