<x-layouts.app
    title="About Us"
    description="Learn about Hotel Benizia, a premier hospitality destination in Asaba, Delta State, Nigeria. Our story, vision, mission, values, and commitment to exceptional guest experiences.">

    <x-page-hero
        title="About Hotel Benizia"
        subtitle="A premier hotel in Asaba, Delta State — built on hospitality, comfort, and an unwavering commitment to exceptional guest experiences."
        image="https://hotelbenizia.ng/img/property/hotel-compound.webp"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'About Us']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'About Us', 'url' => route('about')],
    ]" />

    {{-- Our Story --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-16 lg:grid-cols-2 lg:items-center">
                <div>
                    <x-section-intro
                        eyebrow="Our Story"
                        title="Hospitality at the Heart of Asaba"
                        text="Hotel Benizia was founded with a simple mission: to deliver a world-class guest experience in the heart of Asaba, Delta State."
                        align="left"
                    />
                    <div class="mt-8 space-y-4 text-gray-600 leading-relaxed">
                        <p>Located in Central Area, Asaba, Hotel Benizia has grown to become one of Delta State's premier hospitality destinations. Every aspect of the hotel — from our comfortable rooms and serviced apartments to our 24-hour restaurant and event facilities — is designed to make guests feel at home.</p>
                        <p>We serve business travelers, families, couples, long-stay apartment guests, and VIP visitors seeking quality accommodation and genuine Nigerian hospitality. Whether you need a room for the night or a serviced apartment for an extended stay in Asaba, Hotel Benizia is your home away from home.</p>
                    </div>
                </div>
                <div class="rounded-3xl overflow-hidden">
                    <img
                        src="https://hotelbenizia.ng/img/property/hotel-exterior.webp"
                        alt="Hotel Benizia exterior — Asaba, Delta State"
                        class="w-full h-96 object-cover"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Vision & Mission --}}
    <section class="py-20 px-4 bg-benizia-cream">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 sm:grid-cols-2">
                <div class="rounded-3xl bg-white p-10 shadow-sm ring-1 ring-gray-100">
                    <div class="mb-6 overflow-hidden rounded-2xl h-48">
                        <img
                            src="https://hotelbenizia.ng/img/property/hotel-compound.webp"
                            alt="Hotel Benizia Vision — looking towards excellence in Asaba"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Our Vision</h2>
                    <p class="text-gray-600 leading-relaxed">To be the leading hospitality brand in Asaba and Delta State — recognized for excellence, comfort, and a genuinely warm Nigerian welcome that guests remember long after they check out.</p>
                </div>
                <div class="rounded-3xl bg-benizia-green p-10 shadow-sm">
                    <div class="mb-6 overflow-hidden rounded-2xl h-48">
                        <img
                            src="https://hotelbenizia.ng/img/property/hotel-entrance.webp"
                            alt="Hotel Benizia Mission — delivering excellence every day in Asaba"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <h2 class="font-serif text-2xl text-white mb-4">Our Mission</h2>
                    <p class="text-white/80 leading-relaxed">To provide every guest with a seamless, memorable stay — through comfortable rooms, excellent dining, reliable facilities, and attentive service delivered with warmth and professionalism every single day.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Our Values"
                title="What We Stand For"
                text="Every decision at Hotel Benizia is guided by these core values."
            />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['title' => 'Warmth',        'desc' => 'We treat every guest like family — with genuine care, attentiveness, and a smile.'],
                    ['title' => 'Excellence',     'desc' => 'We hold ourselves to a high standard in every area — rooms, dining, service, and facilities.'],
                    ['title' => 'Integrity',      'desc' => 'We are honest, transparent, and consistent in how we serve our guests and community.'],
                    ['title' => 'Comfort',        'desc' => 'We design every space and service to ensure guests feel relaxed, rested, and at ease.'],
                ] as $value)
                    <div class="rounded-3xl bg-benizia-cream p-8 text-center">
                        <div class="mb-4 h-1 w-12 bg-benizia-gold mx-auto rounded-full"></div>
                        <h3 class="font-serif text-xl text-benizia-charcoal mb-3">{{ $value['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us / Stats --}}
    <section class="py-20 px-4 bg-benizia-charcoal text-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Why Choose Hotel Benizia"
                title="The Benizia Difference"
                text="More than a hotel — a complete hospitality experience in Asaba, Delta State."
            />
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['stat' => '5+',       'label' => 'Room Categories'],
                    ['stat' => '24/7',     'label' => 'Restaurant & Bar'],
                    ['stat' => '100%',     'label' => 'Backup Power'],
                    ['stat' => '4.9★',    'label' => 'Guest Rating'],
                ] as $stat)
                    <div class="text-center rounded-3xl bg-white/5 p-8">
                        <p class="font-serif text-4xl text-benizia-gold mb-2">{{ $stat['stat'] }}</p>
                        <p class="text-sm text-white/70">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if(isset($testimonials) && $testimonials->count())
        <section class="py-20 px-4 bg-white">
            <div class="mx-auto max-w-7xl">
                <x-section-intro
                    eyebrow="Guest Reviews"
                    title="What Our Guests Say"
                    text="Real feedback from guests who have stayed at Hotel Benizia in Asaba."
                />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($testimonials as $testimonial)
                        <x-testimonial :testimonial="$testimonial" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta title="Stay at Hotel Benizia" text="Experience Asaba's premier hotel. Book your room today and discover the difference." />

</x-layouts.app>
