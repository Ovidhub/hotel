<x-layouts.app
    :title="$room->name . ' — ' . $room->category"
    :description="$room->excerpt ?? 'Stay in the ' . $room->name . ' at Hotel Benizia, Asaba, Delta State. ' . ($room->category ?? '') . '. Breakfast and pool access included.'">

    <x-page-hero
        :title="$room->name"
        :subtitle="$room->category . ' · ' . $room->price_formatted . '/night'"
        :image="$room->imageUrl()"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Rooms & Suites', 'url' => route('rooms.index')],
            ['label' => $room->name],
        ]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Rooms & Suites', 'url' => route('rooms.index')],
        ['name' => $room->name, 'url' => route('rooms.show', $room)],
    ]" />

    {{-- aggregateRating omitted until real guest reviews are collected. --}}
    <x-schema.product
        :name="$room->name"
        :description="$room->excerpt ?? $room->description"
        :image="$room->imageUrl()"
        :price="$room->price"
        :url="route('rooms.show', $room)"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-5xl">

            {{-- Gallery --}}
            @php $galleryImages = $room->galleryUrls(); @endphp
            @if(count($galleryImages) > 1)
                <div class="mb-12" x-data="{ index: 0, images: {{ \Illuminate\Support\Js::from($galleryImages) }} }">
                    {{-- Main image with prev/next controls --}}
                    <div class="relative overflow-hidden rounded-3xl h-96 mb-3 group">
                        <img
                            :src="images[index]"
                            alt="{{ $room->name }} at Hotel Benizia — Asaba"
                            class="h-full w-full object-cover transition duration-500"
                        >
                        <button type="button" aria-label="Previous image"
                            x-on:click="index = (index - 1 + images.length) % images.length"
                            class="absolute left-3 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/80 text-benizia-charcoal shadow hover:bg-white">
                            &#8249;
                        </button>
                        <button type="button" aria-label="Next image"
                            x-on:click="index = (index + 1) % images.length"
                            class="absolute right-3 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/80 text-benizia-charcoal shadow hover:bg-white">
                            &#8250;
                        </button>
                    </div>
                    {{-- Thumbnails (scrollable) --}}
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($galleryImages as $i => $img)
                            <button
                                type="button"
                                x-on:click="index = {{ $i }}"
                                class="flex-shrink-0 h-20 w-28 overflow-hidden rounded-xl border-2 transition"
                                :class="index === {{ $i }} ? 'border-benizia-gold' : 'border-transparent hover:border-benizia-gold/50'"
                                aria-label="View gallery image {{ $i + 1 }}"
                            >
                                <img
                                    src="{{ $img }}"
                                    alt="{{ $room->name }} gallery — Hotel Benizia"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mb-12 overflow-hidden rounded-3xl h-96">
                    <img
                        src="{{ $galleryImages[0] ?? $room->imageUrl() }}"
                        alt="{{ $room->name }} at Hotel Benizia — Asaba"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    >
                </div>
            @endif

            {{-- Price --}}
            <div class="flex flex-wrap items-center gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold">From</p>
                    <p class="font-serif text-3xl text-benizia-charcoal">{{ $room->price_formatted }} <span class="text-base font-normal text-gray-400">/ night</span></p>
                </div>
            </div>

            {{-- Room specs --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-12">
                @if($room->guests)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Guests</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->guests }}</p>
                    </div>
                @endif
                @if($room->beds)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Beds</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->beds }}</p>
                    </div>
                @endif
                @if($room->baths)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Baths</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->baths }}</p>
                    </div>
                @endif
                @if($room->size)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Size</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $room->size }}</p>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="prose prose-lg max-w-none text-gray-700 mb-12">
                <p>{{ $room->description }}</p>
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

            {{-- Policies --}}
            @if($room->policies)
                <div class="mb-12">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-6">Room Policies</h2>
                    <ul class="space-y-2">
                        @foreach($room->policies as $policy)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 text-benizia-gold flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                {{ $policy }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Best For chips --}}
            @if($room->best_for)
                <div class="mb-12">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Best For</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($room->best_for as $tag)
                            <span class="rounded-full bg-benizia-cream px-4 py-2 text-sm font-medium text-benizia-green">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Book CTA button --}}
            @php
                $bookUrl = Route::has('booking.create')
                    ? route('booking.create', ['type' => 'room', 'slug' => $room->slug])
                    : route('rooms.index');
            @endphp
            <div class="mb-12">
                <a href="{{ $bookUrl }}"
                   class="inline-block rounded-full bg-benizia-green px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal text-lg">
                    Book This Room
                </a>
            </div>
        </div>
    </section>

    {{-- Similar rooms --}}
    @if($similar && $similar->count())
        <section class="py-16 px-4 bg-benizia-cream">
            <div class="mx-auto max-w-7xl">
                <h2 class="font-serif text-3xl text-benizia-charcoal text-center mb-10">Other Rooms You May Like</h2>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($similar as $similarRoom)
                        <x-room-card :room="$similarRoom" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta title="Ready to Book This Room?" text="Contact our reservations team or book directly. Breakfast and pool access included with every stay." />

</x-layouts.app>
