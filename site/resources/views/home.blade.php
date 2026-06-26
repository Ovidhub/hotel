<x-layouts.app
    title="Hotel Benizia — Luxury Hotel in Asaba, Delta State"
    description="Hotel Benizia offers premium rooms, suites, apartments, restaurant, pool, and event halls in Asaba, Delta State, Nigeria.">

    {{-- Hero --}}
    <x-page-hero
        title="Welcome to Hotel Benizia"
        subtitle="Asaba's premier hotel — luxury rooms, suites, fine dining, and world-class hospitality in the heart of Delta State."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg"
    />

    {{-- Rooms section --}}
    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Accommodation"
                title="Rooms & Suites"
                text="From our Deluxe Classic to the Penthouse Suite — every room is built for comfort, privacy, and a memorable Asaba stay."
            />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($rooms as $room)
                    <x-room-card :room="$room" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('rooms.index') }}"
                   class="inline-block rounded-full border-2 border-benizia-green px-8 py-3 font-bold text-benizia-green transition hover:bg-benizia-green hover:text-white">
                    View All Rooms
                </a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <x-cta title="Ready to Experience Hotel Benizia?" text="Book your room today and enjoy breakfast, pool access, and world-class hospitality in Asaba, Delta State." />

</x-layouts.app>
