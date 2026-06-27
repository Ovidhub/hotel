<x-layouts.app
    :title="'Book ' . $bookable->name"
    :description="'Reserve the ' . $bookable->name . ' at Hotel Benizia, Asaba. Secure your stay with a ' . $commitmentPercent . '% commitment fee. Balance due at check-in.'"
    robots="noindex, follow">

    <x-page-hero
        title="Reserve Your Stay"
        :subtitle="'Booking: ' . $bookable->name . ' — ' . $priceFormatted . ' / night'"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $type === 'room' ? 'Rooms & Suites' : 'HB Apartments',
             'url'   => $type === 'room' ? route('rooms.index') : route('apartments.index')],
            ['label' => $bookable->name,
             'url'   => $type === 'room' ? route('rooms.show', $bookable) : route('apartments.show', $bookable)],
            ['label' => 'Reserve'],
        ]"
    />

    @php
        $defaultCheckIn  = request()->query('check_in',  now()->addDay()->format('Y-m-d'));
        $defaultCheckOut = request()->query('check_out',  now()->addDays(2)->format('Y-m-d'));
        $defaultGuests   = request()->query('guests', 1);
    @endphp

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">

            <div class="grid gap-12 lg:grid-cols-5">

                {{-- ── LEFT: Property summary card ───────────────────────────────── --}}
                <aside class="lg:col-span-2 space-y-6">

                    {{-- Image --}}
                    <div class="overflow-hidden rounded-3xl aspect-[4/3]">
                        <img
                            src="{{ method_exists($bookable, 'imageUrl') ? $bookable->imageUrl() : $bookable->image }}"
                            alt="{{ $bookable->name }} — Hotel Benizia"
                            class="h-full w-full object-cover"
                            loading="eager"
                        >
                    </div>

                    {{-- Property details --}}
                    <div class="rounded-3xl bg-benizia-cream p-6">
                        <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold mb-1">
                            {{ $type === 'room' ? ($bookable->category ?? 'Room') : ($bookable->type ?? 'Apartment') }}
                        </p>
                        <h2 class="font-serif text-2xl text-benizia-charcoal mb-3">{{ $bookable->name }}</h2>

                        {{-- Price per night --}}
                        <p class="text-3xl font-serif text-benizia-green mb-1">
                            {{ $priceFormatted }}
                            <span class="text-sm font-normal text-gray-400">/ night</span>
                        </p>

                        {{-- Specs --}}
                        <div class="mt-4 flex flex-wrap gap-3">
                            @if($type === 'room')
                                @if($bookable->guests)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-benizia-charcoal border border-gray-100">
                                        {{ $bookable->guests }} Guests
                                    </span>
                                @endif
                                @if($bookable->beds)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-benizia-charcoal border border-gray-100">
                                        {{ $bookable->beds }} Beds
                                    </span>
                                @endif
                                @if($bookable->size)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-benizia-charcoal border border-gray-100">
                                        {{ $bookable->size }}
                                    </span>
                                @endif
                            @else
                                @if($bookable->occupancy)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-benizia-charcoal border border-gray-100">
                                        Up to {{ $bookable->occupancy }} guests
                                    </span>
                                @endif
                                @if($bookable->bedrooms)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-benizia-charcoal border border-gray-100">
                                        {{ $bookable->bedrooms }} Bedrooms
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Commitment fee policy banner --}}
                    <div class="rounded-2xl border border-benizia-gold/40 bg-benizia-gold/5 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-benizia-gold mb-2">Booking Policy</p>
                        <p class="text-sm text-benizia-charcoal leading-relaxed">
                            A <strong>{{ $commitmentPercent }}% commitment fee</strong> secures your booking;
                            balance due at check-in. Free cancellation up to
                            <strong>{{ $cancellationHours }}h</strong> before arrival.
                        </p>
                        <p class="mt-2 text-xs text-gray-500">{{ $balanceNote }}</p>
                    </div>

                </aside>

                {{-- ── RIGHT: Booking form with Alpine.js live summary ────────────── --}}
                <div class="lg:col-span-3">
                    <div
                        class="rounded-3xl bg-benizia-cream p-8 lg:p-10"
                        x-data="{
                            price: {{ $price }},
                            commitment: {{ $commitmentPercent }},
                            checkIn: '{{ old('check_in', $defaultCheckIn) }}',
                            checkOut: '{{ old('check_out', $defaultCheckOut) }}',

                            get nights() {
                                if (!this.checkIn || !this.checkOut) return 1;
                                const a = new Date(this.checkIn);
                                const b = new Date(this.checkOut);
                                const diff = Math.round((b - a) / 86400000);
                                return diff < 1 ? 1 : diff;
                            },
                            get total() { return this.price * this.nights; },
                            get fee()   { return Math.round(this.total * this.commitment / 100); },
                            get balance() { return this.total - this.fee; },

                            fmt(n) {
                                return '₦' + n.toLocaleString('en-NG');
                            }
                        }"
                    >
                        <h2 class="font-serif text-2xl text-benizia-charcoal mb-1">Complete Your Reservation</h2>
                        <p class="text-sm text-gray-500 mb-8">Fill in your stay details below. Fields marked <span class="text-red-500">*</span> are required.</p>

                        <form
                            method="POST"
                            action="{{ route('booking.store') }}"
                            class="space-y-6"
                        >
                            @csrf

                            {{-- Hidden fields: type + slug --}}
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="slug" value="{{ $bookable->slug }}">

                            {{-- ── Dates ── --}}
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="check_in" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                        Check-in Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="check_in"
                                        name="check_in"
                                        x-model="checkIn"
                                        value="{{ old('check_in', $defaultCheckIn) }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                    >
                                    @error('check_in')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="check_out" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                        Check-out Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="check_out"
                                        name="check_out"
                                        x-model="checkOut"
                                        value="{{ old('check_out', $defaultCheckOut) }}"
                                        min="{{ now()->addDays(2)->format('Y-m-d') }}"
                                        :min="checkIn ? new Date(new Date(checkIn).getTime() + 86400000).toISOString().split('T')[0] : '{{ now()->addDay()->format('Y-m-d') }}'"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                    >
                                    @error('check_out')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ── Guests ── --}}
                            <div>
                                <label for="guests" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                    Number of Guests <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="guests"
                                    name="guests"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                >
                                    @for($i = 1; $i <= ($type === 'room' ? ($bookable->guests ?? 4) : ($bookable->occupancy ?? 6)); $i++)
                                        <option value="{{ $i }}" {{ old('guests', $defaultGuests) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('guests')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── Live price summary (Alpine) ── --}}
                            <div class="rounded-2xl border border-benizia-green/20 bg-white p-5 space-y-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-benizia-green mb-3">Stay Summary</p>

                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="nights + (nights === 1 ? ' night' : ' nights') + ' × ' + fmt(price)"></span>
                                    <span class="font-semibold text-benizia-charcoal" x-text="fmt(total)"></span>
                                </div>

                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="commitment + '% commitment fee (due now)'"></span>
                                    <span class="font-semibold text-benizia-gold" x-text="fmt(fee)"></span>
                                </div>

                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Balance due at check-in</span>
                                    <span class="font-semibold text-benizia-charcoal" x-text="fmt(balance)"></span>
                                </div>

                                <div class="border-t border-gray-100 pt-3 flex justify-between">
                                    <span class="text-sm font-bold text-benizia-charcoal">Total Stay</span>
                                    <span class="font-serif text-lg font-bold text-benizia-green" x-text="fmt(total)"></span>
                                </div>
                            </div>

                            {{-- ── Guest details ── --}}
                            <div class="border-t border-gray-100 pt-6">
                                <p class="text-sm font-bold text-benizia-charcoal mb-4">Guest Information</p>

                                {{-- Full name --}}
                                <div class="mb-4">
                                    <label for="guest_name" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="guest_name"
                                        name="guest_name"
                                        value="{{ old('guest_name') }}"
                                        placeholder="As on your ID"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                    >
                                    @error('guest_name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-4">
                                    <label for="guest_email" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="guest_email"
                                        name="guest_email"
                                        value="{{ old('guest_email') }}"
                                        placeholder="your@email.com"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                    >
                                    @error('guest_email')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="mb-4">
                                    <label for="guest_phone" class="block text-sm font-semibold text-benizia-charcoal mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        id="guest_phone"
                                        name="guest_phone"
                                        value="{{ old('guest_phone') }}"
                                        placeholder="+234 800 000 0000"
                                        required
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                                    >
                                    @error('guest_phone')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ── Policy acknowledgement ── --}}
                            <p class="text-xs text-gray-500 leading-relaxed">
                                By proceeding you agree to our booking policy: a
                                <strong>{{ $commitmentPercent }}%</strong> commitment fee is charged to secure
                                your reservation. Cancellations made less than
                                <strong>{{ $cancellationHours }} hours</strong> before check-in are non-refundable.
                            </p>

                            {{-- ── Submit ── --}}
                            <button
                                type="submit"
                                class="w-full rounded-full bg-benizia-green py-4 font-bold text-white text-base transition hover:bg-benizia-charcoal"
                            >
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-cta
        title="Need Help Booking?"
        text="Call our reservations team on {{ config('hotel.phone') }} — we're available 24 hours, 7 days a week."
    />

</x-layouts.app>
