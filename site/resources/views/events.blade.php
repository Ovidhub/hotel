<x-layouts.app
    title="Events & Conference Venue in Asaba"
    description="Hotel Benizia's event halls and boardroom in Asaba, Delta State host weddings, conferences, corporate meetings, product launches, and private functions.">

    <x-page-hero
        title="Events &amp; Conferences"
        subtitle="Versatile event spaces in Asaba for weddings, conferences, boardroom meetings, and private functions — backed by Hotel Benizia's hospitality team."
        image="https://hotelbenizia.ng/wp-content/uploads/2025/06/benizia-hall-780x520.jpg"
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Events']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">
            <x-section-intro
                eyebrow="Events"
                title="Your Event, Our Venue"
                text="Hotel Benizia offers flexible, air-conditioned event spaces in Asaba for every occasion — small or large."
            />

            <div class="mt-12 grid gap-8 sm:grid-cols-2">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Event Halls</h2>
                    <p class="text-gray-600 leading-relaxed">Air-conditioned halls that can accommodate wedding receptions, corporate conferences, product launches, and large private gatherings. Full catering available from our 24-hour restaurant.</p>
                </div>
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-4">Boardroom</h2>
                    <p class="text-gray-600 leading-relaxed">A professional boardroom for business meetings, strategy sessions, and executive briefings. Equipped with reliable power, high-speed WiFi, and audio-visual support.</p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('contact') }}"
                   class="inline-block rounded-full bg-benizia-green px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal">
                    Enquire About Events
                </a>
            </div>
        </div>
    </section>

    <x-cta title="Plan Your Event at Hotel Benizia" text="Weddings, conferences, boardroom meetings, and private functions in Asaba. Contact us to start planning." />

</x-layouts.app>
