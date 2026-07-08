<x-layouts.app
    title="Restaurant & Bar in Asaba"
    description="Hotel Benizia's 24-hour restaurant and bar in Asaba serves Nigerian and continental cuisine. VIP bar, pool bar, and live band Wed, Fri &amp; Sat nights.">

    <x-page-hero
        title="Restaurant & Bar in Asaba"
        subtitle="A 24-hour dining experience in the heart of Delta State — Nigerian and continental cuisine, crafted cocktails, VIP bar, pool bar, and live band nights."
        image="https://hotelbenizia.ng/img/property/hotel-restaurant.webp"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Restaurant & Bar']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Restaurant & Bar', 'url' => route('restaurant')],
    ]" />

    {{-- Intro --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Dining"
                title="24-Hour Restaurant & Bar"
                text="Whether it's an early breakfast, a business lunch, or a late-night drink — Hotel Benizia's kitchen and bar never close. Hotel guests, apartment guests, and walk-in visitors are all welcome, every hour of the day."
            />

            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['icon' => '🍽️', 'title' => 'The Restaurant', 'desc' => 'Nigerian and continental cuisine prepared by experienced chefs. Complimentary breakfast included with every room booking.'],
                    ['icon' => '🍸', 'title' => 'VIP Bar', 'desc' => 'An exclusive bar experience with premium spirits, curated cocktails, and a refined atmosphere for distinguished guests.'],
                    ['icon' => '🏊', 'title' => 'Pool Bar', 'desc' => 'Relax poolside with refreshing drinks, mocktails, and light bites in a scenic outdoor setting.'],
                    ['icon' => '🎵', 'title' => 'Live Band Nights', 'desc' => 'Live music every Wednesday, Friday, and Saturday evening. Nigerian and Afrobeats sounds in a vibrant setting.'],
                ] as $feature)
                    <div class="rounded-3xl bg-benizia-cream p-8">
                        <div class="text-3xl mb-4" aria-hidden="true">{{ $feature['icon'] }}</div>
                        <h2 class="font-serif text-xl text-benizia-charcoal mb-3">{{ $feature['title'] }}</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Ambiance gallery --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Ambiance"
                title="Dine in Style"
                text="From our pool bar to the VIP lounge, every dining space at Hotel Benizia is designed for comfort and atmosphere."
            />
            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach([
                    ['src' => 'https://hotelbenizia.ng/img/property/hotel-bar.webp', 'alt' => 'Hotel Benizia VIP bar — Asaba, Delta State'],
                    ['src' => 'https://hotelbenizia.ng/img/property/hotel-restaurant-2.webp', 'alt' => 'Hotel Benizia restaurant dining — Asaba'],
                    ['src' => 'https://hotelbenizia.ng/img/property/dish-1.webp', 'alt' => 'Freshly plated dish at Hotel Benizia — Asaba'],
                    ['src' => 'https://hotelbenizia.ng/img/property/dish-2.webp', 'alt' => 'Nigerian and continental dishes at Hotel Benizia'],
                    ['src' => 'https://hotelbenizia.ng/img/property/chef.webp', 'alt' => 'Hotel Benizia chef preparing fresh cuisine'],
                    ['src' => 'https://hotelbenizia.ng/img/property/hotel-poolbar.webp', 'alt' => 'Hotel Benizia poolside bar — Asaba'],
                ] as $img)
                    <div class="overflow-hidden rounded-2xl aspect-video">
                        <img
                            src="{{ $img['src'] }}"
                            alt="{{ $img['alt'] }}"
                            class="h-full w-full object-cover transition duration-500 hover:scale-105"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Hours --}}
    <section class="py-16 px-4 bg-benizia-cream">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="font-serif text-3xl text-benizia-charcoal mb-6">Opening Hours</h2>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="font-semibold text-benizia-charcoal">Restaurant</p>
                    <p class="text-sm text-gray-500 mt-1">24 Hours · 7 Days</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="font-semibold text-benizia-charcoal">Bar</p>
                    <p class="text-sm text-gray-500 mt-1">Open All Day</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <p class="font-semibold text-benizia-charcoal">Live Band</p>
                    <p class="text-sm text-gray-500 mt-1">Wed · Fri · Sat</p>
                </div>
            </div>
        </div>
    </section>

    <x-cta title="Dine at Hotel Benizia Tonight" text="Open 24 hours. Nigerian and continental cuisine, cocktails, and a welcoming bar atmosphere in Asaba, Delta State." />

</x-layouts.app>
