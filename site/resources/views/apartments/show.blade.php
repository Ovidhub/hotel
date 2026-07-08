<x-layouts.app
    :title="$apartment->name . ' — ' . $apartment->type"
    :description="$apartment->description ?? 'Book the ' . $apartment->name . ' at HB Apartments by Hotel Benizia in Asaba, Delta State. Hotel-backed serviced apartment.'">

    <x-page-hero
        :title="$apartment->name"
        :subtitle="($apartment->type ?? 'Serviced Apartment') . ' · ' . $apartment->price_formatted . '/night'"
        :image="$apartment->imageUrl()"
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
        :image="$apartment->imageUrl()"
        :price="$apartment->price"
        :url="route('apartments.show', $apartment)"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-5xl">

            {{-- Gallery --}}
            @php $galleryImages = $apartment->galleryUrls(); @endphp
            @if(count($galleryImages) > 1)
                <div class="mb-12" x-data="{ index: 0, images: {{ \Illuminate\Support\Js::from($galleryImages) }} }">
                    <div class="relative overflow-hidden rounded-3xl h-[28rem] mb-3 group bg-benizia-charcoal/5">
                        <img
                            :src="images[index]"
                            alt="{{ $apartment->name }} — HB Apartments, Asaba"
                            class="h-full w-full object-contain transition duration-500"
                        >
                        <button type="button" aria-label="Previous image"
                            x-on:click="index = (index - 1 + images.length) % images.length"
                            class="absolute left-3 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/80 text-benizia-charcoal shadow hover:bg-white">
                            &#8249;
                        </button>
                        <button type="button" aria-label="Next image"
                            x-on:click="index = (index + 1) % images.length"
                            class="absolute right-3 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/80 text-benizia-charcoal shadow hover:bg-white">
                            &#8250;
                        </button>
                    </div>
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($galleryImages as $i => $img)
                            <button
                                type="button"
                                x-on:click="index = {{ $i }}"
                                class="flex-shrink-0 h-20 w-28 overflow-hidden rounded-xl border-2 transition"
                                :class="index === {{ $i }} ? 'border-benizia-gold' : 'border-transparent hover:border-benizia-gold/50'"
                                aria-label="View gallery image {{ $i + 1 }}"
                            >
                                <img
                                    src="{{ $img }}"
                                    alt="{{ $apartment->name }} gallery — HB Apartments"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mb-12 overflow-hidden rounded-3xl h-[28rem] bg-benizia-charcoal/5">
                    <img
                        src="{{ $galleryImages[0] ?? $apartment->imageUrl() }}"
                        alt="{{ $apartment->name }} — HB Apartments, Asaba"
                        class="h-full w-full object-contain"
                        loading="lazy"
                    >
                </div>
            @endif

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

    {{-- HB Apartments booking contact --}}
    <section class="px-4 pb-20 pt-4 bg-white">
        <div class="mx-auto max-w-4xl">
            <x-apartment-contact />
        </div>
    </section>

    <x-cta title="Looking for a Serviced Apartment in Asaba?" text="HB Apartments offers hotel-backed apartment stays for extended visits, families, and business travelers in Asaba." />

</x-layouts.app>
