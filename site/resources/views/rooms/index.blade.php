<x-layouts.app
    title="Rooms & Suites in Asaba"
    description="Browse all rooms and suites at Hotel Benizia in Asaba, Delta State. From Deluxe Classic to Penthouse Suite — breakfast and pool access included with every stay.">

    <x-page-hero
        title="Rooms & Suites in Asaba"
        subtitle="Every room at Hotel Benizia includes complimentary breakfast, pool access, and gym access. Choose the experience that suits your stay in Delta State."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Rooms & Suites']]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Rooms & Suites', 'url' => route('rooms.index')],
    ]" />

    {{-- Intro section --}}
    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Accommodation"
                title="Choose Your Room"
                text="Five room categories, each designed with comfort, privacy, and value in mind. All include breakfast, swimming pool access, and gym access."
            />

            {{-- Category filter chips (static) --}}
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @foreach(['All', 'Comfort Room', 'Premium Room', 'Signature Room', 'Executive Suite', 'Signature Suite'] as $cat)
                    <span class="rounded-full px-4 py-1.5 text-sm font-semibold cursor-pointer
                        {{ $cat === 'All' ? 'bg-benizia-green text-white' : 'bg-benizia-cream text-benizia-charcoal hover:bg-benizia-green hover:text-white' }}
                        transition">
                        {{ $cat }}
                    </span>
                @endforeach
            </div>

            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($rooms as $room)
                    <x-room-card :room="$room" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- What's included band --}}
    <section class="py-16 px-4 bg-benizia-green text-white">
        <div class="mx-auto max-w-7xl">
            <h2 class="font-serif text-3xl text-center text-white mb-10">Every Room Includes</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
                @foreach(['Complimentary Breakfast', 'Swimming Pool Access', 'Gym Access', 'Free High-Speed WiFi', '24-Hour Room Service', '24-Hour Security'] as $item)
                    <div class="flex flex-col items-center text-center gap-2">
                        <div class="h-10 w-10 rounded-full bg-benizia-gold/20 flex items-center justify-center">
                            <svg class="h-5 w-5 text-benizia-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <span class="text-sm text-white/90">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta title="Book Your Room at Hotel Benizia" text="Rooms from ₦30,000/night. Breakfast, pool access, and gym access included with every booking." />

</x-layouts.app>
