<x-layouts.app
    :title="$apartment->name . ' — ' . $apartment->type"
    :description="$apartment->description ?? 'Book the ' . $apartment->name . ' at HB Apartments by Hotel Benizia in Asaba, Delta State. Hotel-backed serviced apartment.'">

    <x-page-hero
        :title="$apartment->name"
        :subtitle="($apartment->type ?? 'Serviced Apartment') . ' · ' . $apartment->price_formatted . '/night'"
        :image="$apartment->image"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'HB Apartments', 'url' => route('apartments.index')],
            ['label' => $apartment->name],
        ]"
    />

    <x-schema.breadcrumb :items="[
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'HB Apartments', 'url' => route('apartments.index')],
        ['name' => $apartment->name, 'url' => route('apartments.show', $apartment)],
    ]" />

    <x-schema.product
        :name="$apartment->name"
        :description="$apartment->description ?? $apartment->name"
        :image="$apartment->image"
        :price="$apartment->price"
        :url="route('apartments.show', $apartment)"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-5xl">

            {{-- Gallery --}}
            @php
                $galleryImages = $apartment->gallery ?? [$apartment->image];
            @endphp
            <div class="mb-12">
                <div class="overflow-hidden rounded-3xl h-80 mb-3">
                    <img
                        src="{{ $galleryImages[0] ?? $apartment->image }}"
                        alt="{{ $apartment->name }} — HB Apartments, Asaba"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    >
                </div>
                @if(count($galleryImages) > 1)
                    <div class="flex gap-3">
                        @foreach(array_slice($galleryImages, 1) as $img)
                            <div class="flex-1 overflow-hidden rounded-xl h-24">
                                <img
                                    src="{{ $img }}"
                                    alt="{{ $apartment->name }} gallery — HB Apartments"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Price & status --}}
            <div class="flex flex-wrap items-center gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold">From</p>
                    <p class="font-serif text-3xl text-benizia-charcoal">{{ $apartment->price_formatted }} <span class="text-base font-normal text-gray-400">/ night</span></p>
                </div>
                @php
                    $statusClasses = match(strtolower($apartment->status ?? 'available')) {
                        'available'   => 'bg-emerald-100 text-emerald-800',
                        'occupied'    => 'bg-amber-100 text-amber-800',
                        'maintenance' => 'bg-gray-100 text-gray-600',
                        default       => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $statusClasses }}">
                    {{ $apartment->status ?? 'Available' }}
                </span>
            </div>

            {{-- Description --}}
            <div class="prose prose-lg max-w-none text-gray-700 mb-12">
                <p>{{ $apartment->description }}</p>
            </div>

            {{-- Specs --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 mb-12">
                @if($apartment->bedrooms)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Bedrooms</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $apartment->bedrooms }}</p>
                    </div>
                @endif
                @if($apartment->bathrooms)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Bathrooms</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $apartment->bathrooms }}</p>
                    </div>
                @endif
                @if($apartment->occupancy)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Max Guests</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $apartment->occupancy }}</p>
                    </div>
                @endif
            </div>

            {{-- Amenities --}}
            @if($apartment->amenities)
                <div class="mb-12">
                    <h2 class="font-serif text-2xl text-benizia-charcoal mb-6">Apartment Amenities</h2>
                    <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($apartment->amenities as $amenity)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <span class="h-2 w-2 rounded-full bg-benizia-gold flex-shrink-0"></span>
                                {{ $amenity }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Book / Enquire CTA --}}
            @php
                $bookUrl = Route::has('booking.create')
                    ? route('booking.create', ['type' => 'apartment', 'slug' => $apartment->slug])
                    : route('apartments.index');
            @endphp
            @if(strtolower($apartment->status ?? 'available') === 'available')
                <a href="{{ $bookUrl }}"
                   class="inline-block rounded-full bg-benizia-green px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal text-lg">
                    Book This Apartment
                </a>
            @else
                <a href="{{ route('contact') }}"
                   class="inline-block rounded-full bg-benizia-gold px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal text-lg">
                    Enquire About This Apartment
                </a>
            @endif

        </div>
    </section>

    <x-cta title="Looking for a Serviced Apartment in Asaba?" text="HB Apartments offers hotel-backed apartment stays for extended visits, families, and business travelers in Asaba." />

</x-layouts.app>
