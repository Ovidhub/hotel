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

            @if (session('error'))
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

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
                        x-data="bookingPicker({
                            price: {{ $price }},
                            commitment: {{ $commitmentPercent }},
                            unavailable: {{ \Illuminate\Support\Js::from($unavailableDates) }},
                            minDate: '{{ $minDate }}',
                            checkIn: '{{ old('check_in', request()->query('check_in', '')) }}',
                            checkOut: '{{ old('check_out', request()->query('check_out', '')) }}',
                        })"
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

                            {{-- ── Dates: availability calendar ── --}}
                            <input type="hidden" name="check_in" x-model="checkIn">
                            <input type="hidden" name="check_out" x-model="checkOut">

                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="block text-sm font-semibold text-benizia-charcoal">
                                        Select your dates <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" x-show="checkIn || checkOut" x-on:click="clearDates()"
                                            class="text-xs font-semibold text-benizia-green hover:underline">Clear</button>
                                </div>

                                {{-- Selected range chips --}}
                                <div class="mb-3 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-gray-200 bg-white px-4 py-2.5">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Check-in</p>
                                        <p class="text-sm font-medium text-benizia-charcoal" x-text="pretty(checkIn)"></p>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 bg-white px-4 py-2.5">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Check-out</p>
                                        <p class="text-sm font-medium text-benizia-charcoal" x-text="pretty(checkOut)"></p>
                                    </div>
                                </div>

                                {{-- Calendar --}}
                                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <button type="button" x-on:click="prevMonth()" x-bind:disabled="!canGoPrev"
                                                class="grid h-8 w-8 place-items-center rounded-full text-benizia-charcoal hover:bg-benizia-cream disabled:opacity-30 disabled:hover:bg-transparent"
                                                aria-label="Previous month">&#8249;</button>
                                        <p class="text-sm font-semibold text-benizia-charcoal" x-text="monthLabel"></p>
                                        <button type="button" x-on:click="nextMonth()"
                                                class="grid h-8 w-8 place-items-center rounded-full text-benizia-charcoal hover:bg-benizia-cream"
                                                aria-label="Next month">&#8250;</button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1 text-center">
                                        <template x-for="d in dow" :key="d">
                                            <div class="py-1 text-[11px] font-semibold uppercase text-gray-400" x-text="d"></div>
                                        </template>
                                        {{-- leading blanks --}}
                                        <template x-for="b in leadingBlanks" :key="'b' + b"><div></div></template>
                                        {{-- day cells --}}
                                        <template x-for="day in days" :key="day">
                                            <button type="button"
                                                    x-on:click="select(day)"
                                                    x-bind:disabled="isDisabled(day)"
                                                    class="aspect-square rounded-lg text-sm transition"
                                                    x-bind:class="{
                                                        'bg-benizia-green text-white font-semibold': isCheckIn(day) || isCheckOut(day),
                                                        'bg-benizia-green/10 text-benizia-charcoal': inRange(day),
                                                        'text-gray-300 line-through cursor-not-allowed': isDisabled(day),
                                                        'text-benizia-charcoal hover:bg-benizia-cream': !isDisabled(day) && !isCheckIn(day) && !isCheckOut(day) && !inRange(day)
                                                    }"
                                                    x-text="parseInt(day.slice(-2))"></button>
                                        </template>
                                    </div>

                                    <p class="mt-3 flex items-center gap-2 text-[11px] text-gray-400">
                                        <span class="inline-block h-3 w-3 rounded line-through bg-gray-100"></span>
                                        Crossed-out dates are unavailable.
                                    </p>
                                </div>

                                @error('check_in') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                @error('check_out') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                            <div class="rounded-2xl border border-benizia-green/20 bg-white p-5">
                                <p class="text-xs font-bold uppercase tracking-widest text-benizia-green mb-3">Stay Summary</p>

                                <p x-show="!hasRange" class="text-sm text-gray-500">Select your check-in and check-out dates above to see pricing.</p>

                                <div x-show="hasRange" x-cloak class="space-y-3">
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
                                x-bind:disabled="!hasRange"
                                class="w-full rounded-full bg-benizia-green py-4 font-bold text-white text-base transition hover:bg-benizia-charcoal disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-benizia-green"
                            >
                                <span x-show="hasRange">Proceed to Checkout</span>
                                <span x-show="!hasRange" x-cloak>Select dates to continue</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-cta
        title="Need Help Booking?"
        text="{{ $type === 'apartment'
            ? 'Call HB Apartments on ' . config('hotel.apartments.phone') . ' or email ' . config('hotel.apartments.email') . ' for apartment reservations.'
            : 'Call our reservations team on ' . config('hotel.phone') . ' — we are available 24 hours, 7 days a week.' }}"
    />

</x-layouts.app>
