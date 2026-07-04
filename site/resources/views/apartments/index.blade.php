<x-layouts.app
    title="HB Serviced Apartments in Asaba"
    description="HB Apartments by Hotel Benizia offers serviced short-let apartments in Asaba, Delta State. Ideal for extended stays, families, and business travelers.">

    <x-page-hero
        title="Serviced Apartments in Asaba"
        subtitle="HB Apartments by Hotel Benizia — serviced short-let apartments in Asaba, Delta State for extended stays, families, and business travelers, with hotel-grade support and amenities."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'HB Apartments']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'HB Apartments', 'url' => route('apartments.index')],
    ]" />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="HB Apartments"
                title="Our Serviced Apartments"
                text="Three apartment categories built for privacy, independence, and comfort in Asaba."
            />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($apartments as $apartment)
                    <x-apartment-card :apartment="$apartment" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Why choose HB Apartments ──────────────────────────────────── --}}
    <section class="py-20 px-4 bg-benizia-cream" aria-label="Why choose HB Apartments">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="The HB Apartments Difference"
                title="A Home, with Hotel Support"
                text="Our serviced apartments in Asaba combine the space and independence of a private home with the comfort and service of Hotel Benizia."
            />

            @php
                $benefits = [
                    ['title' => 'Fully-Equipped Kitchenette', 'desc' => 'Cook your own meals or order from our 24-hour restaurant — the choice is yours.'],
                    ['title' => 'Daily Housekeeping', 'desc' => 'Hotel-grade cleaning and fresh linens, so your apartment always feels spotless.'],
                    ['title' => 'Great Long-Stay Value', 'desc' => 'Ideal for relocations, projects, and extended visits to Asaba — more space for your money.'],
                    ['title' => 'Hotel Facilities Included', 'desc' => 'Enjoy the swimming pool, gym, restaurant, and bar just like our hotel guests.'],
                    ['title' => '24/7 Power & Security', 'desc' => 'Reliable round-the-clock power supply, trained security, and CCTV for peace of mind.'],
                    ['title' => 'Central Asaba Location', 'desc' => 'Close to the business district, government offices, and Asaba International Airport.'],
                ];
            @endphp

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($benefits as $benefit)
                    <div class="rounded-3xl bg-white p-7 ring-1 ring-gray-100 shadow-sm">
                        <div class="mb-4 grid h-11 w-11 place-items-center rounded-xl bg-benizia-green/10 text-benizia-green">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-serif text-lg text-benizia-charcoal">{{ $benefit['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $benefit['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Video highlight — HB Apartments tour --}}
    <x-video-tour
        eyebrow="Take a Tour"
        title="Inside HB Apartments"
        text="A short walk-through of HB Apartments — the building, the pool, and the serviced spaces built for longer, independent stays."
        src="https://hotelbenizia.ng/videos/apartment.mp4"
        poster="https://hotelbenizia.ng/img/property/apartment-exterior.webp"
    />

    <x-cta title="Looking for a Serviced Apartment in Asaba?" text="HB Apartments offers hotel-backed apartment stays for extended visits, families, and business travelers." />

</x-layouts.app>
