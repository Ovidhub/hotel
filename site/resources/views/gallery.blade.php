<x-layouts.app
    title="Photo Gallery — Hotel Benizia Asaba"
    description="Browse the Hotel Benizia photo gallery. See our rooms, suites, restaurant, swimming pool, event halls, and grounds in Asaba, Delta State.">

    <x-page-hero
        title="Photo Gallery"
        subtitle="A visual tour of Hotel Benizia — rooms, pool, restaurant, event spaces, and the grounds in Asaba, Delta State."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizia-swimming-pool-and-bar-1200x959.jpg"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Gallery']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="Gallery"
                title="Inside Hotel Benizia"
                text="Photos of our rooms, suites, pool, restaurant, event facilities, and more."
            />

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @php
                    $images = [
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/Deluxe-classic-550x824.jpg', 'alt' => 'Deluxe Classic Room'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/Hotel-benizia-room1-550x826.jpg', 'alt' => 'Deluxe Premium Room'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/Alcove-room-550x825.jpg', 'alt' => 'Alcove Room'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/hotel-benizia-room2-550x827.jpg', 'alt' => 'Diplomatic Suite'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/Hotel-benizia-room3-550x825.jpg', 'alt' => 'Penthouse Suite'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizia-swimming-pool-and-bar-1200x959.jpg', 'alt' => 'Swimming Pool & Bar'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/swimming-pool-550x413.jpg', 'alt' => 'Swimming Pool'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/Bar.jpg', 'alt' => 'Hotel Bar'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/benizia-hall-780x520.jpg', 'alt' => 'Event Hall'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg', 'alt' => 'Hotel Benizia Exterior'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizi-entrance-370x554.jpg', 'alt' => 'Hotel Entrance'],
                        ['src' => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/balcony-homepage-pics-600x798.jpg', 'alt' => 'Balcony'],
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

    <x-cta title="Experience Hotel Benizia in Person" text="Book a stay and see it all for yourself. Rooms from ₦30,000/night in Asaba, Delta State." />

</x-layouts.app>
