<x-layouts.app
    title="Restaurant &amp; Bar in Asaba"
    description="Hotel Benizia's 24-hour restaurant and bar in Asaba serves Nigerian and continental cuisine. VIP bar, pool bar, and live band Wed, Fri &amp; Sat nights.">

    <x-page-hero
        title="Restaurant &amp; Bar in Asaba"
        subtitle="A 24-hour dining experience in the heart of Delta State — Nigerian and continental cuisine, crafted cocktails, VIP bar, pool bar, and live band nights."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/06/Bar.jpg"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Restaurant & Bar']]"
    />

    {{-- Intro --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Dining"
                title="24-Hour Restaurant &amp; Bar"
                text="Whether it's an early breakfast, a business lunch, or a late-night drink — Hotel Benizia's kitchen and bar never close. Serving guests and walk-in visitors every hour of the day."
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

    {{-- Menu Section --}}
    <section class="py-20 px-4 bg-benizia-charcoal text-white">
        <div class="mx-auto max-w-5xl">
            <x-section-intro
                eyebrow="Our Menu"
                title="What We Serve"
                text="A curated selection of Nigerian and continental dishes, available 24 hours a day."
            />

            <div class="mt-12 space-y-12">
                @foreach($menu as $section)
                    <div>
                        <h2 class="font-serif text-2xl text-benizia-gold mb-6 pb-3 border-b border-white/10">
                            {{ $section['category'] }}
                        </h2>
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach($section['items'] as $item)
                                <div class="flex justify-between gap-4 py-4 border-b border-white/5">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-white/60 mt-1 leading-relaxed">{{ $item['description'] }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="font-semibold text-benizia-gold text-sm">{{ $item['price'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3" loading="lazy">
                @foreach([
                    ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/Bar.jpg', 'alt' => 'Hotel Benizia Bar — Asaba, Delta State'],
                    ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizia-swimming-pool-and-bar-1200x959.jpg', 'alt' => 'Hotel Benizia Swimming Pool & Bar — Asaba'],
                    ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/swimming-pool-550x413.jpg', 'alt' => 'Hotel Benizia Pool Area — Asaba'],
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
