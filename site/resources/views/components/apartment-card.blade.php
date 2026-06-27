@props([
    'apartment',
])

@php
    $detailUrl  = \Illuminate\Support\Facades\Route::has('apartments.show')
        ? route('apartments.show', $apartment)
        : '#';
    $bookingUrl = \Illuminate\Support\Facades\Route::has('booking.create')
        ? route('booking.create', ['type' => 'apartment', 'slug' => $apartment->slug])
        : '#';
    $image   = (method_exists($apartment, 'imageUrl') ? $apartment->imageUrl() : $apartment->image) ?? 'https://images.pexels.com/photos/1454806/pexels-photo-1454806.jpeg?auto=compress&cs=tinysrgb&w=800';
    $name    = $apartment->name ?? 'Apartment';
    $status  = $apartment->status ?? 'available';

    $statusConfig = match(strtolower($status)) {
        'available'   => ['label' => 'Available',   'class' => 'bg-emerald-100 text-emerald-800'],
        'occupied'    => ['label' => 'Occupied',    'class' => 'bg-amber-100 text-amber-800'],
        'maintenance' => ['label' => 'Maintenance', 'class' => 'bg-gray-100 text-gray-600'],
        default       => ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-600'],
    };

    $price   = method_exists($apartment, 'getAttribute') ? $apartment->price_formatted : null;
    $excerpt = $apartment->excerpt ?? $apartment->description ?? '';
    $beds    = $apartment->beds ?? null;
    $guests  = $apartment->guests ?? null;
    $size    = $apartment->size ?? null;
@endphp

<article class="group overflow-hidden rounded-3xl bg-white ring-1 ring-gray-100 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(29,92,82,0.12)]">

    {{-- Image --}}
    <a href="{{ $detailUrl }}" class="relative block h-64 overflow-hidden" aria-label="View {{ $name }} details">
        <img
            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
            src="{{ $image }}"
            alt="{{ $name }} — HB Apartments"
            loading="lazy"
        >
        {{-- Status badge --}}
        <span class="absolute left-4 top-4 rounded-full px-3 py-1.5 text-xs font-semibold shadow {{ $statusConfig['class'] }}">
            {{ $statusConfig['label'] }}
        </span>
    </a>

    {{-- Card body --}}
    <div class="p-6">
        {{-- Price --}}
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-benizia-gold">from</p>
            <p class="font-serif mt-0.5 text-2xl text-benizia-charcoal">
                {{ $price ?? 'Contact us' }} <span class="text-base font-normal text-gray-400">/ night</span>
            </p>
        </div>

        {{-- Apartment name --}}
        <h3 class="font-serif mt-4 text-xl text-benizia-charcoal">{{ $name }}</h3>

        {{-- Excerpt --}}
        @if($excerpt)
            <p class="mt-2 text-sm leading-6 text-gray-500 line-clamp-2">{{ $excerpt }}</p>
        @endif

        {{-- Specs chips --}}
        @if($beds || $guests || $size)
            <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-500">
                @if($guests)
                    <span class="rounded-full bg-benizia-cream px-3 py-1.5">{{ $guests }} guests</span>
                @endif
                @if($beds)
                    <span class="rounded-full bg-benizia-cream px-3 py-1.5">{{ $beds }}</span>
                @endif
                @if($size)
                    <span class="rounded-full bg-benizia-cream px-3 py-1.5">{{ $size }}</span>
                @endif
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            @if(strtolower($status) === 'available')
                <a href="{{ $bookingUrl }}"
                   class="flex-1 rounded-full bg-benizia-green py-3 text-center text-sm font-bold text-white transition hover:bg-benizia-charcoal">
                    Book Now
                </a>
            @else
                <button
                    disabled
                    class="flex-1 rounded-full bg-gray-200 py-3 text-center text-sm font-bold text-gray-400 cursor-not-allowed"
                    aria-label="Apartment not available">
                    Not Available
                </button>
            @endif
            <a href="{{ $detailUrl }}"
               class="flex-1 rounded-full border border-benizia-gold py-3 text-center text-sm font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white hover:border-transparent">
                View Details
            </a>
        </div>
    </div>
</article>
