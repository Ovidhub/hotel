<x-layouts.app
    title="HB Apartments — Serviced Apartments in Asaba, Delta State"
    description="HB Apartments by Hotel Benizia offers serviced short-let apartments in Asaba, Delta State. Ideal for extended stays, families, and business travelers.">

    <x-page-hero
        title="HB Apartments"
        subtitle="Serviced apartments in Asaba for extended stays, families, and business travelers — with hotel-grade support and amenities."
        :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Apartments']]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-7xl">
            <x-section-intro
                eyebrow="HB Apartments"
                title="Our Serviced Apartments"
                text="Three apartment categories built for privacy, independence, and comfort in Asaba."
            />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($apartments as $apartment)
                    <x-apartment-card :apartment="$apartment" />
                @endforeach
            </div>
        </div>
    </section>

    <x-cta title="Looking for a Serviced Apartment in Asaba?" text="HB Apartments offers hotel-backed apartment stays for extended visits, families, and business travelers." />

</x-layouts.app>
