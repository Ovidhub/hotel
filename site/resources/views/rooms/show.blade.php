<x-layouts.app
    :title="$room->name . ' — Hotel Benizia Asaba'"
    :description="$room->excerpt ?? 'Stay in the ' . $room->name . ' at Hotel Benizia, Asaba, Delta State. ' . ($room->category ?? '')">

    <x-page-hero
        :title="$room->name"
        :subtitle="$room->excerpt"
        :image="$room->image"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Rooms', 'url' => route('rooms.index')],
            ['label' => $room->name],
        ]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">

            {{-- Price & rating --}}
            <div class="flex flex-wrap items-center gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold">From</p>
                    <p class="font-serif text-3xl text-benizia-charcoal">{{ $room->price_formatted }} <span class="text-base font-normal text-gray-400">/ night</span></p>
                </div>
                @if($room->rating)
                    <div class="flex items-center gap-2">
                        <x-rating-stars :rating="$room->rating" />
                        <span class="font-semibold text-benizia-green">{{ number_format((float)$room->rating, 1) }}</span>
                        @if($room->reviews)
                            <span class="text-sm text-gray-400">({{ $room->reviews }} reviews)</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="prose prose-lg max-w-none text-gray-700 mb-12">
                <p>{{ $room->description }}</p>
            </div>

            {{-- Room specs --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-12">
                @if($room->guests)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Guests</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->guests }}</p>
                    </div>
                @endif
                @if($room->beds)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Beds</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->beds }}</p>
                    </div>
                @endif
                @if($room->baths)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Baths</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->baths }}</p>
                    </div>
                @endif
                @if($room->size)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Size</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->size }}</p>
                    </div>
                @endif
            </div>

            {{-- Amenities --}}
            @if($room->amenities)
                <div class="mb-12">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-6">Room Amenities</h2>
                    <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($room->amenities as $amenity)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <span class="h-2 w-2 rounded-full bg-benizia-gold flex-shrink-0"></span>
                                {{ $amenity }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- What's included --}}
            @if($room->includes)
                <div class="mb-12">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-6">What's Included</h2>
                    <ul class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach($room->includes as $include)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 text-benizia-green flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                {{ $include }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Book CTA --}}
            @if(Route::has('booking'))
                <a href="{{ route('booking') }}?room={{ $room->slug }}"
                   class="inline-block rounded-full bg-benizia-green px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal">
                    Book This Room
                </a>
            @endif
        </div>
    </section>

    <x-cta title="Ready to Book This Room?" text="Contact our reservations team or book directly. Breakfast and pool access included." />

</x-layouts.app>
