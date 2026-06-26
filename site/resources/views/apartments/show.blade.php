<x-layouts.app
    :title="$apartment->name . ' — HB Apartments, Asaba'"
    :description="$apartment->description ?? 'Book the ' . $apartment->name . ' at HB Apartments by Hotel Benizia in Asaba, Delta State.'">

    <x-page-hero
        :title="$apartment->name"
        :subtitle="$apartment->description"
        :image="$apartment->image"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Apartments', 'url' => route('apartments.index')],
            ['label' => $apartment->name],
        ]"
    />

    <section class="py-20 px-4">
        <div class="mx-auto max-w-5xl">

            {{-- Price & status --}}
            <div class="flex flex-wrap items-center gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold">From</p>
                    <p class="font-serif text-3xl text-benizia-charcoal">{{ $apartment->price_formatted }} <span class="text-base font-normal text-gray-400">/ night</span></p>
                </div>
                <span class="rounded-full bg-benizia-cream px-4 py-1.5 text-sm font-semibold text-benizia-green">
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
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Bedrooms</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $apartment->bedrooms }}</p>
                    </div>
                @endif
                @if($apartment->bathrooms)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Bathrooms</p>
                        <p class="font-serif text-2xl text-benizia-charcoal">{{ $apartment->bathrooms }}</p>
                    </div>
                @endif
                @if($apartment->occupancy)
                    <div class="rounded-2xl bg-benizia-cream p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Occupancy</p>
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

            {{-- Book CTA --}}
            @if(Route::has('booking'))
                <a href="{{ route('booking') }}?apartment={{ $apartment->slug }}"
                   class="inline-block rounded-full bg-benizia-green px-10 py-4 font-bold text-white transition hover:bg-benizia-charcoal">
                    Enquire About This Apartment
                </a>
            @endif

        </div>
    </section>

    <x-cta title="Enquire About This Apartment" text="Contact our team to check availability and confirm your stay." />

</x-layouts.app>
