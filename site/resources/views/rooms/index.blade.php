<x-layouts.app
    title="Rooms &amp; Suites — Hotel Benizia Asaba"
    description="Browse all rooms and suites at Hotel Benizia in Asaba, Delta State. From Deluxe Classic to Penthouse Suite — breakfast and pool access included.">

    <x-page-hero
        title="Rooms &amp; Suites"
        subtitle="Every room at Hotel Benizia includes complimentary breakfast, pool access, and gym access. Choose the experience that suits your stay."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Rooms']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Accommodation"
                title="Choose Your Room"
                text="Five room categories, each designed with comfort, privacy, and value in mind."
            />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($rooms as $room)
                    <x-room-card :room="$room" />
                @endforeach
            </div>
        </div>
    </section>

    <x-cta title="Book Your Room at Hotel Benizia" text="Rooms from ₦30,000/night. Breakfast, pool access, and gym access included." />

</x-layouts.app>
