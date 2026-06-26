<x-layouts.app
    title="Restaurant &amp; Bar — Hotel Benizia Asaba"
    description="Hotel Benizia's 24-hour restaurant and bar in Asaba, Delta State serves Nigerian and continental cuisine. Open to guests and visitors all day and night.">

    <x-page-hero
        title="Restaurant &amp; Bar"
        subtitle="A 24-hour dining experience in Asaba — Nigerian and continental cuisine, crafted cocktails, and a welcoming bar atmosphere."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/06/Bar.jpg"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Restaurant']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">
            <x-section-intro
                eyebrow="Dining"
                title="24-Hour Restaurant &amp; Bar"
                text="Whether it's an early breakfast, a business lunch, or a late-night drink — Hotel Benizia's kitchen and bar never close."
            />

            <div class="mt-12 grid gap-8 sm:grid-cols-2">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">The Restaurant</h2>
                    <p class="text-gray-600 leading-relaxed">Enjoy a curated menu of Nigerian and continental dishes prepared by our experienced kitchen team. Complimentary breakfast is included with every room booking.</p>
                </div>
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">The Bar</h2>
                    <p class="text-gray-600 leading-relaxed">Unwind at the Hotel Benizia bar with cocktails, mocktails, and a curated drinks selection. Available around the clock for guests and visitors.</p>
                </div>
            </div>

            <div class="mt-12 rounded-3xl bg-benizia-cream p-8">
                <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Opening Hours</h2>
                <p class="text-gray-700">The restaurant and bar are open <strong>24 hours a day, 7 days a week</strong>, serving guests and walk-in visitors at Hotel Benizia, Asaba.</p>
            </div>
        </div>
    </section>

    <x-cta title="Dine at Hotel Benizia Tonight" text="Open 24 hours. Nigerian and continental cuisine, cocktails, and a welcoming bar atmosphere." />

</x-layouts.app>
