<x-layouts.app
    title="Photo Gallery — Hotel & Apartments"
    description="Browse the Hotel Benizia photo gallery — rooms, suites, serviced apartments, restaurant, swimming pool, event halls, and grounds in Asaba, Delta State.">

    <x-page-hero
        title="Photo Gallery"
        subtitle="A visual tour of Hotel Benizia — rooms, suites, serviced apartments, pool, restaurant, and event spaces in Asaba, Delta State."
        image="https://hotelbenizia.ng/img/property/hotel-compound.webp"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Gallery']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Gallery', 'url' => route('gallery')],
    ]" />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="The Hotel"
                title="Inside Hotel Benizia"
                text="Photos of our rooms, suites, pool, restaurant, event facilities, and more."
            />

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @php
                    $images = [
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-exterior.webp', 'alt' => 'Hotel Benizia Exterior'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-entrance.webp', 'alt' => 'Hotel Benizia Entrance'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-compound.webp', 'alt' => 'Hotel Benizia Grounds'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-aerial.webp', 'alt' => 'Hotel Benizia Aerial View'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-pool.webp', 'alt' => 'Hotel Benizia Swimming Pool'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-poolbar.webp', 'alt' => 'Hotel Benizia Poolside Bar'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-restaurant.webp', 'alt' => 'Hotel Benizia Restaurant'],
                        ['src' => 'https://hotelbenizia.ng/img/property/hotel-bar.webp', 'alt' => 'Hotel Benizia VIP Bar'],
                        ['src' => 'https://hotelbenizia.ng/img/property/dish-1.webp', 'alt' => 'Freshly Plated Dish'],
                        ['src' => 'https://hotelbenizia.ng/img/property/dish-3.webp', 'alt' => 'Nigerian & Continental Cuisine'],
                        ['src' => 'https://hotelbenizia.ng/img/rooms/deluxe-classic/1.webp', 'alt' => 'Deluxe Classic Room'],
                        ['src' => 'https://hotelbenizia.ng/img/rooms/deluxe-classic/3.webp', 'alt' => 'Deluxe Classic Room Interior'],
                        ['src' => 'https://hotelbenizia.ng/img/rooms/alcove-room/1.webp', 'alt' => 'Alcove Room'],
                        ['src' => 'https://hotelbenizia.ng/img/rooms/diplomatic-suite/1.webp', 'alt' => 'Diplomatic Suite'],
                        ['src' => 'https://hotelbenizia.ng/img/rooms/presidential-suite/1.webp', 'alt' => 'Presidential Suite'],
                        ['src' => 'https://hotelbenizia.ng/img/property/apartment-exterior.webp', 'alt' => 'HB Apartments Building'],
                        ['src' => 'https://hotelbenizia.ng/img/property/apartment-poolside.webp', 'alt' => 'HB Apartments Poolside'],
                        ['src' => 'https://hotelbenizia.ng/img/property/apartment-dining.webp', 'alt' => 'HB Apartments Dining Area'],
                        ['src' => 'https://hotelbenizia.ng/img/property/apartment-terrace.webp', 'alt' => 'HB Apartments Terrace'],
                        ['src' => 'https://hotelbenizia.ng/img/property/apartment-courtyard.webp', 'alt' => 'HB Apartments Courtyard'],
                    ];
                @endphp
                @foreach($images as $img)
                    <div class="overflow-hidden rounded-2xl bg-gray-100 aspect-square">
                        <img
                            src="{{ $img['src'] }}"
                            alt="{{ $img['alt'] }} — Hotel Benizia Asaba"
                            class="h-full w-full object-cover transition duration-500 hover:scale-105"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── HB Apartments gallery ──────────────────────────────────────── --}}
    @php
        $apartmentImages = collect($apartments ?? [])
            ->flatMap(fn ($apt) => collect($apt->galleryUrls())->map(fn ($src) => ['src' => $src, 'alt' => $apt->name]))
            ->unique('src')
            ->values();
    @endphp
    @if($apartmentImages->isNotEmpty())
    <section class="py-20 px-4 bg-benizia-cream" aria-label="HB Apartments gallery">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="HB Apartments"
                title="Our Serviced Apartments"
                text="Inside HB Apartments by Hotel Benizia — serviced short-let apartments in Asaba for families, business travelers, and extended stays."
            />

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($apartmentImages as $img)
                    <div class="overflow-hidden rounded-2xl bg-gray-100 aspect-square">
                        <img
                            src="{{ $img['src'] }}"
                            alt="{{ $img['alt'] }} — serviced apartment in Asaba, Hotel Benizia"
                            class="h-full w-full object-cover transition duration-500 hover:scale-105"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('apartments.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-3 font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white">
                    View All Apartments
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <x-cta title="Experience Hotel Benizia in Person" text="Book a stay and see it all for yourself. Rooms from ₦30,000/night in Asaba, Delta State." />

</x-layouts.app>
