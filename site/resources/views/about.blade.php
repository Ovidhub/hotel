<x-layouts.app
    title="About Hotel Benizia — Asaba, Delta State"
    description="Learn about Hotel Benizia, a premier hospitality destination in Asaba, Delta State, Nigeria. Our mission, values, and commitment to exceptional guest experiences.">

    <x-page-hero
        title="About Hotel Benizia"
        subtitle="A premier hotel in Asaba, Delta State — built on hospitality, comfort, and a commitment to exceptional guest experiences."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/06/our-vision-550x412.jpg"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'About']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">
            <div class="grid gap-16 lg:grid-cols-2 lg:items-center">
                <div>
                    <x-section-intro
                        eyebrow="Our Story"
                        title="Hospitality at the Heart of Asaba"
                        text="Hotel Benizia was founded with a simple mission: to deliver a world-class guest experience in the heart of Asaba, Delta State."
                        align="left"
                    />
                    <div class="mt-8 space-y-4 text-gray-600 leading-relaxed">
                        <p>Located off Summit Road, Asaba, Hotel Benizia offers a full-service hotel experience including comfortable rooms, fine dining, event facilities, swimming pool, gym, and 24-hour security.</p>
                        <p>We serve business travelers, families, couples, and VIP guests seeking quality accommodation and hospitality in Asaba and across Delta State.</p>
                    </div>
                </div>
                <div class="rounded-3xl overflow-hidden">
                    <img
                        src="https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg"
                        alt="Hotel Benizia exterior — Asaba, Delta State"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    <x-cta title="Stay at Hotel Benizia" text="Experience Asaba's premier hotel. Book your room today." />

</x-layouts.app>
