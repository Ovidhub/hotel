<x-layouts.app
    title="Luxury Hotel & Serviced Apartments in Asaba, Delta State"
    description="Hotel Benizia is a luxury hotel and serviced apartments in Asaba, Delta State — premium rooms, short-let apartments, fine dining, pool, spa, and event halls. Book direct."
    image="https://hotelbenizia.ng/img/property/hotel-exterior.webp">

    {{-- ════════════════════════════════════════════════════════════════
         1. HERO SECTION
         Full-viewport hero with dark overlay, serif H1, and booking bar
    ════════════════════════════════════════════════════════════════ --}}
    <section
        class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden"
        aria-label="Hotel Benizia hero"
    >
        {{-- Hero background image --}}
        <img
            src="https://hotelbenizia.ng/img/property/hotel-exterior.webp"
            alt="Hotel Benizia luxury hotel interior — Asaba, Delta State"
            class="absolute inset-0 h-full w-full object-cover"
            fetchpriority="high"
        >

        {{-- Dark overlay with gradient --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/75" aria-hidden="true"></div>

        {{-- Hero content --}}
        <div class="relative z-10 mx-auto max-w-5xl px-4 pt-28 pb-12 text-center text-white">

            {{-- Gold eyebrow --}}
            <p class="mb-5 text-xs font-bold uppercase tracking-[0.4em] text-benizia-gold">
                Luxury Hotel &middot; Asaba, Delta State
            </p>

            {{-- Page H1 — ONLY one on this page --}}
            <h1 class="font-serif text-4xl font-semibold leading-tight text-white drop-shadow-lg sm:text-5xl md:text-6xl lg:text-7xl">
                Experience Luxury<br class="hidden sm:block"> in the Heart of Asaba
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-white/85 sm:text-lg">
                Premium rooms &amp; suites, gourmet dining, sparkling pool, and world-class hospitality —
                everything you need for an unforgettable stay in Delta State.
            </p>

            {{-- Hero CTA buttons --}}
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a
                    href="{{ route('rooms.index') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-benizia-gold px-8 py-4 text-sm font-bold text-white shadow-lg transition hover:bg-white hover:text-benizia-green hover:shadow-xl"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    Book Your Stay
                </a>
                <a
                    href="{{ route('rooms.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-white/60 bg-white/10 px-8 py-4 text-sm font-bold text-white backdrop-blur-sm transition hover:border-white hover:bg-white hover:text-benizia-green"
                >
                    Explore Rooms
                </a>
            </div>
        </div>

        {{-- ── AVAILABILITY / BOOKING SEARCH BAR ─────────────────────── --}}
        <div class="relative z-10 w-full px-4 pb-10 sm:pb-16">
            <div class="mx-auto max-w-5xl">
                <div class="rounded-2xl bg-white/10 p-1 shadow-2xl backdrop-blur-md ring-1 ring-white/20">
                    <form
                        method="GET"
                        action="{{ route('rooms.index') }}"
                        class="grid grid-cols-1 gap-px overflow-hidden rounded-xl bg-white/20 sm:grid-cols-2 lg:grid-cols-4"
                        aria-label="Availability search"
                    >
                        {{-- Check-in --}}
                        <div class="flex flex-col bg-white px-5 py-4">
                            <label for="check_in" class="mb-1 text-xs font-bold uppercase tracking-widest text-benizia-green">Check-in</label>
                            <input
                                type="date"
                                id="check_in"
                                name="check_in"
                                class="text-sm font-medium text-benizia-charcoal outline-none focus:ring-0"
                                min="{{ date('Y-m-d') }}"
                                aria-label="Check-in date"
                            >
                        </div>

                        {{-- Check-out --}}
                        <div class="flex flex-col bg-white px-5 py-4">
                            <label for="check_out" class="mb-1 text-xs font-bold uppercase tracking-widest text-benizia-green">Check-out</label>
                            <input
                                type="date"
                                id="check_out"
                                name="check_out"
                                class="text-sm font-medium text-benizia-charcoal outline-none focus:ring-0"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                aria-label="Check-out date"
                            >
                        </div>

                        {{-- Guests --}}
                        <div class="flex flex-col bg-white px-5 py-4">
                            <label for="guests" class="mb-1 text-xs font-bold uppercase tracking-widest text-benizia-green">Guests</label>
                            <select
                                id="guests"
                                name="guests"
                                class="text-sm font-medium text-benizia-charcoal outline-none focus:ring-0"
                                aria-label="Number of guests"
                            >
                                <option value="1">1 Guest</option>
                                <option value="2" selected>2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5">5+ Guests</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-stretch bg-benizia-green">
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 px-6 py-4 text-sm font-bold text-white transition hover:bg-benizia-charcoal"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                                Check Availability
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-4 left-1/2 z-10 -translate-x-1/2 animate-bounce hidden lg:flex flex-col items-center gap-1" aria-hidden="true">
            <span class="text-xs text-white/60 tracking-widest uppercase">Scroll</span>
            <svg class="h-4 w-4 text-benizia-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         2. INTRO / ABOUT STRIP
         Split image + text with stat counters
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-white" aria-label="About Hotel Benizia">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-12 lg:grid-cols-2">

                {{-- Image side --}}
                <div class="relative">
                    <img
                        src="https://hotelbenizia.ng/img/property/hotel-entrance.webp"
                        alt="Hotel Benizia welcoming entrance and host — Asaba, Delta State"
                        class="w-full rounded-2xl object-cover shadow-xl"
                        style="aspect-ratio:4/3"
                        loading="lazy"
                    >
                    {{-- Gold accent badge --}}
                    <div class="absolute -bottom-5 -right-5 hidden rounded-2xl bg-benizia-green px-6 py-5 text-white shadow-xl lg:block">
                        <p class="font-serif text-3xl font-semibold text-benizia-gold">₦30,000</p>
                        <p class="mt-0.5 text-xs font-medium text-white/80">Rooms from / night</p>
                    </div>
                </div>

                {{-- Text side --}}
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.35em] text-benizia-gold">Welcome to Hotel Benizia</p>
                    <h2 class="font-serif mt-3 text-3xl leading-tight text-benizia-charcoal md:text-5xl">
                        Asaba's Premier<br>Luxury Hotel
                    </h2>
                    <p class="mt-5 text-base leading-7 text-gray-500">
                        Nestled in the heart of Asaba, Delta State, Hotel Benizia delivers an exceptional hospitality experience
                        combining premium accommodation, gourmet cuisine, a sparkling swimming pool, world-class event halls,
                        and attentive 24/7 service.
                    </p>
                    <p class="mt-4 text-base leading-7 text-gray-500">
                        Whether you're here for business, leisure, or a special occasion, every stay at Hotel Benizia is
                        crafted to exceed your expectations — from the moment you arrive to the moment you check out.
                    </p>

                    {{-- Stat counters --}}
                    <div class="mt-8 grid grid-cols-3 gap-6 border-t border-gray-100 pt-8">
                        <div>
                            <p class="font-serif text-3xl font-semibold text-benizia-green">5</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gray-400">Room Types</p>
                        </div>
                        <div>
                            <p class="font-serif text-3xl font-semibold text-benizia-green">24/7</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gray-400">Service</p>
                        </div>
                        <div>
                            <p class="font-serif text-3xl font-semibold text-benizia-green">Free</p>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gray-400">Breakfast &amp; Pool</p>
                        </div>
                    </div>

                    <a
                        href="{{ route('about') }}"
                        class="mt-8 inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-7 py-3 text-sm font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
                    >
                        Our Story
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         3. AMENITY RIBBON
         Quick highlights band
    ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-benizia-cream py-10 px-4 border-y border-benizia-gold/20" aria-label="Key amenities">
        <div class="mx-auto max-w-7xl">
            <x-amenity-ribbon />
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         4. ROOM SHOWCASE
         Grid of room cards from DB
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-white" aria-label="Rooms and suites">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Accommodation"
                title="Our Rooms & Suites"
                text="From the warm Deluxe Classic to the exclusive Penthouse Suite — every room at Hotel Benizia is built for comfort, privacy, and a memorable Asaba experience."
            />
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($rooms as $room)
                    <x-room-card :room="$room" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a
                    href="{{ route('rooms.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-3 font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
                >
                    View All Rooms
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         4b. HB APARTMENTS SHOWCASE
         Serviced apartments from DB (parity with rooms)
    ════════════════════════════════════════════════════════════════ --}}
    @if($apartments->isNotEmpty())
    <section class="py-20 px-4 bg-benizia-cream" aria-label="Serviced apartments">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="HB Apartments"
                title="Serviced Apartments in Asaba"
                text="Prefer the space and privacy of a home? HB Apartments by Hotel Benizia offers serviced short-let apartments in Asaba — ideal for families, business travelers, and extended stays, with hotel-grade support."
            />
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($apartments as $apartment)
                    <x-apartment-card :apartment="$apartment" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a
                    href="{{ route('apartments.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-3 font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
                >
                    View All Apartments
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ════════════════════════════════════════════════════════════════
         5. DINING & BAR FEATURE
         Split section: image + copy about restaurant & bar
    ════════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-benizia-charcoal py-20 px-4 text-white" aria-label="Dining and bar">
        {{-- Background texture --}}
        <div class="pointer-events-none absolute inset-0 opacity-10" aria-hidden="true">
            <div class="absolute right-0 top-0 h-96 w-96 rounded-full bg-benizia-gold blur-3xl"></div>
            <div class="absolute left-0 bottom-0 h-64 w-64 rounded-full bg-benizia-green blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl">
            <div class="grid items-center gap-12 lg:grid-cols-2">

                {{-- Text side --}}
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.35em] text-benizia-gold">Dining &amp; Bar</p>
                    <h2 class="font-serif mt-3 text-3xl leading-tight text-white md:text-5xl">
                        A Restaurant<br>That Never Closes
                    </h2>
                    <p class="mt-5 text-base leading-7 text-white/70">
                        Our 24-hour restaurant serves a rich menu of Nigerian and continental dishes — from the signature
                        Delta Pepper Soup to continental pasta, grilled fish, and the beloved Jollof Rice Royale.
                        Good food is always available, no matter the hour.
                    </p>
                    <p class="mt-4 text-base leading-7 text-white/70">
                        Unwind at our VIP bar with handcrafted cocktails, mocktails, and a curated drink selection.
                        The poolside bar is perfect for evening relaxation, and live band entertainment every
                        <strong class="text-benizia-gold">Wednesday, Friday, and Saturday</strong> sets the perfect mood.
                    </p>

                    {{-- Menu highlights --}}
                    <ul class="mt-8 space-y-3">
                        @php
                            $menuHighlights = [
                                ['Chef Breakfast Platter', 'NGN 8,500'],
                                ['Delta Pepper Soup', 'NGN 10,000'],
                                ['Jollof Rice Royale', 'NGN 9,500'],
                                ['Signature Mocktail', 'NGN 5,500'],
                            ];
                        @endphp
                        @foreach($menuHighlights as [$dish, $price])
                            <li class="flex items-center justify-between border-b border-white/10 pb-3 text-sm">
                                <span class="text-white/85">{{ $dish }}</span>
                                <span class="font-semibold text-benizia-gold">{{ $price }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <a
                        href="{{ route('restaurant') }}"
                        class="mt-8 inline-flex items-center gap-2 rounded-full bg-benizia-gold px-7 py-3 text-sm font-bold text-white transition hover:bg-white hover:text-benizia-green"
                    >
                        View Full Menu
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>

                {{-- Image side --}}
                <div class="relative">
                    <img
                        src="https://hotelbenizia.ng/img/property/hotel-compound.webp"
                        alt="Hotel Benizia grounds and lounge surroundings — Asaba"
                        class="w-full rounded-2xl object-cover shadow-2xl"
                        style="aspect-ratio:4/3"
                        loading="lazy"
                    >
                    <div class="absolute -bottom-4 -left-4 hidden rounded-xl bg-benizia-green px-5 py-4 shadow-xl lg:block">
                        <p class="text-xs font-bold uppercase tracking-widest text-benizia-gold">Open</p>
                        <p class="font-serif text-lg font-semibold text-white">24 Hours</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         6. SERVICES / FACILITIES GRID
         8 cards from $facilities array
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-benizia-cream" aria-label="Hotel facilities and services">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Facilities"
                title="Everything You Need"
                text="Hotel Benizia is designed to give every guest a complete, comfortable, and enjoyable stay — from breakfast to bedtime."
            />
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($facilities as $facility)
                    <div class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-md hover:ring-benizia-green/30">
                        {{-- Icon --}}
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-benizia-green/10 text-benizia-green transition group-hover:bg-benizia-green group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                {!! $facility['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-serif text-base font-semibold text-benizia-charcoal">{{ $facility['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">{{ $facility['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         7. POOL & EXPERIENCE FEATURE
         Full-width image with overlay text
    ════════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden" aria-label="Swimming pool and experience">
        <img
            src="https://hotelbenizia.ng/img/property/hotel-compound.webp"
            alt="Hotel Benizia landscaped grounds and leisure area — Asaba"
            class="h-[480px] w-full object-cover"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent" aria-hidden="true"></div>
        <div class="absolute inset-0 flex items-center px-8 sm:px-16">
            <div class="max-w-lg text-white">
                <p class="text-sm font-bold uppercase tracking-[0.35em] text-benizia-gold">Pool &amp; Leisure</p>
                <h2 class="font-serif mt-3 text-3xl leading-tight text-white md:text-4xl">
                    Relax, Refresh,<br>Rejuvenate
                </h2>
                <p class="mt-4 text-base leading-7 text-white/80">
                    Our sparkling pool and outdoor bar area is the perfect retreat —
                    whether you're unwinding after a long day or soaking in the warm Asaba sunshine.
                </p>
                <a
                    href="{{ route('gallery') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-full border-2 border-benizia-gold px-6 py-3 text-sm font-bold text-benizia-gold transition hover:bg-benizia-gold hover:text-white"
                >
                    View Gallery
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         8. TESTIMONIALS
         Grid of x-testimonial cards
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-white" aria-label="Guest testimonials">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Guest Reviews"
                title="What Our Guests Say"
                text="Real experiences from real guests — discover why Hotel Benizia is Asaba's most talked-about hotel."
            />
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($testimonials as $testimonial)
                    <x-testimonial :testimonial="$testimonial" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         9. LATEST BLOG POSTS
         3 most recent published posts
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-benizia-cream" aria-label="Latest blog posts">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Journal"
                title="From Our Blog"
                text="Tips, guides, and stories from Hotel Benizia — helping you plan the perfect Asaba stay."
            />
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-md">
                        {{-- Post image --}}
                        <a href="{{ route('blog.show', $post) }}" class="relative block overflow-hidden" aria-label="Read: {{ $post->title }}">
                            <img
                                src="{{ $post->image ?? 'https://images.pexels.com/photos/3851937/pexels-photo-3851937.jpeg?auto=compress&cs=tinysrgb&w=700' }}"
                                alt="{{ $post->title }}"
                                class="h-52 w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                            @if($post->category)
                                <span class="absolute left-4 top-4 rounded-full bg-benizia-green px-3 py-1.5 text-xs font-semibold text-white shadow">
                                    {{ $post->category }}
                                </span>
                            @endif
                        </a>

                        {{-- Post content --}}
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                @if($post->published_at)
                                    <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif
                                @if($post->author)
                                    <span>&middot;</span>
                                    <span>{{ $post->author }}</span>
                                @endif
                            </div>
                            <h3 class="font-serif mt-3 text-lg font-semibold text-benizia-charcoal leading-snug">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-benizia-green transition">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            @if($post->excerpt)
                                <p class="mt-2 text-sm leading-6 text-gray-500 line-clamp-3">{{ $post->excerpt }}</p>
                            @endif
                            <a
                                href="{{ route('blog.show', $post) }}"
                                class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-benizia-green transition hover:text-benizia-gold"
                            >
                                Read More
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a
                    href="{{ route('blog.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border-2 border-benizia-green px-8 py-3 font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white"
                >
                    View All Posts
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         10. LOCATION / FINAL CTA
         Address + map embed + CTA band
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-white" aria-label="Location and contact">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Find Us"
                title="Visit Hotel Benizia"
                text="Conveniently located off Summit Road, Asaba, Delta State — easily accessible from the airport and city centre."
            />

            <div class="mt-14 grid items-start gap-10 lg:grid-cols-2">

                {{-- Address & contact --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-benizia-green/10 text-benizia-green">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-benizia-charcoal">Address</p>
                            <p class="mt-0.5 text-sm text-gray-500">{{ config('hotel.address') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-benizia-green/10 text-benizia-green">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-benizia-charcoal">Phone</p>
                            <a href="tel:{{ config('hotel.phone_href') }}" class="mt-0.5 text-sm text-benizia-green hover:text-benizia-gold transition">
                                {{ config('hotel.phone') }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-benizia-green/10 text-benizia-green">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-benizia-charcoal">Email</p>
                            <a href="mailto:{{ config('hotel.email') }}" class="mt-0.5 text-sm text-benizia-green hover:text-benizia-gold transition">
                                {{ config('hotel.email') }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-benizia-green/10 text-benizia-green">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-benizia-charcoal">Check-in / Check-out</p>
                            <p class="mt-0.5 text-sm text-gray-500">Check-in from 2:00 PM &middot; Check-out by 12:00 PM</p>
                        </div>
                    </div>
                </div>

                {{-- Google Map embed --}}
                <div class="overflow-hidden rounded-2xl shadow-lg ring-1 ring-gray-200">
                    <iframe
                        src="https://www.google.com/maps?q=Hotel+Benizia+Asaba&output=embed"
                        width="100%"
                        height="340"
                        style="border:0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Hotel Benizia location map — Asaba, Delta State"
                        aria-label="Map showing Hotel Benizia location in Asaba"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         SEO CONTENT — Hotels & Apartments in Asaba
    ════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 px-4 bg-benizia-cream" aria-label="Hotels and apartments in Asaba">
        <div class="mx-auto max-w-4xl">
            <x-section-intro
                eyebrow="Asaba, Delta State"
                title="Hotels & Apartments in Asaba"
                text="Your home for comfort, business, and leisure in the Delta State capital."
            />

            <div class="mt-8 space-y-5 text-gray-600 leading-relaxed">
                <p>
                    Looking for the <strong>best hotels in Asaba</strong>? Hotel Benizia is a luxury hotel and
                    serviced-apartment destination in Central Area, Asaba, Delta State — minutes from the city's
                    business district, government offices, and Asaba International Airport. Whether you need a
                    one-night stay or an extended visit, we combine five-star comfort with genuine Nigerian
                    hospitality.
                </p>
                <p>
                    Our <a href="{{ route('rooms.index') }}" class="font-semibold text-benizia-green underline decoration-benizia-gold/50 underline-offset-2 hover:text-benizia-gold">luxury hotel rooms in Asaba</a>
                    range from the Deluxe Classic to the exclusive Penthouse Suite, each with complimentary
                    breakfast, swimming-pool access, and high-speed Wi-Fi. For longer stays, our
                    <a href="{{ route('apartments.index') }}" class="font-semibold text-benizia-green underline decoration-benizia-gold/50 underline-offset-2 hover:text-benizia-gold">serviced apartments in Asaba</a>
                    give families and business travelers the independence of a private home with the support of a hotel.
                </p>
                <p>
                    Beyond accommodation, guests enjoy a 24-hour
                    <a href="{{ route('restaurant') }}" class="font-semibold text-benizia-green underline decoration-benizia-gold/50 underline-offset-2 hover:text-benizia-gold">restaurant and bar</a>,
                    a swimming pool, spa, fitness centre, and versatile
                    <a href="{{ route('events') }}" class="font-semibold text-benizia-green underline decoration-benizia-gold/50 underline-offset-2 hover:text-benizia-gold">event halls</a>
                    for weddings, conferences, and meetings — making Hotel Benizia the complete choice for
                    <strong>hotels and apartments in Asaba, Delta State</strong>.
                </p>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════════════
         FINAL CTA BAND
    ════════════════════════════════════════════════════════════════ --}}
    <x-cta
        title="Ready to Experience Hotel Benizia?"
        text="Book your stay today and enjoy complimentary breakfast, pool access, and world-class hospitality in the heart of Asaba, Delta State."
        buttonLabel="Book Now"
        buttonUrl="{{ route('rooms.index') }}"
    />

</x-layouts.app>
