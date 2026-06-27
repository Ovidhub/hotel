<x-layouts.app
    :title="'Complete Your Booking — ' . $booking->ref"
    :description="'Review your booking summary, choose a payment method, and upload your commitment fee proof. Booking ref: ' . $booking->ref"
    robots="noindex, follow">

    <x-page-hero
        title="Complete Your Booking"
        :subtitle="'Booking Reference: ' . $booking->ref"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Checkout'],
        ]"
    />

    <section class="py-20 px-4 bg-white">
        <div class="mx-auto max-w-7xl">

            {{-- ── Flash messages ───────────────────────────────────────────── --}}
            @if(session('status'))
                <div class="mb-8 rounded-2xl border border-benizia-green/30 bg-benizia-green/10 px-5 py-4 text-sm text-benizia-charcoal">
                    <p class="font-semibold text-benizia-green mb-1">Notice</p>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    <p class="font-semibold mb-1">Payment Error</p>
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-12 lg:grid-cols-[1fr_0.44fr]">

                {{-- ── LEFT: Payment method selection + proof upload ────────── --}}
                <div>
                    {{-- Step indicator --}}
                    <div class="mb-10 grid gap-4 md:grid-cols-3">
                        @foreach(['Booking Details','Payment Method','Confirmation'] as $i => $step)
                            <div class="rounded-2xl px-5 py-4 {{ $i === 1 ? 'bg-benizia-green text-white' : 'bg-benizia-cream text-benizia-charcoal' }}">
                                <p class="text-xs font-bold uppercase tracking-[0.18em] opacity-70">Step {{ $i + 1 }}</p>
                                <p class="font-serif mt-1 text-xl">{{ $step }}</p>
                            </div>
                        @endforeach
                    </div>

                    <h1 class="font-serif text-3xl text-benizia-charcoal mb-2">Select Payment Method</h1>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                        Pay <strong>{{ config('hotel.booking.commitment_percent') }}%</strong> of the total as a commitment fee to secure your booking.
                        The balance is due at check-in after confirmation.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('checkout.confirm', ['booking' => $booking->ref]) }}"
                        enctype="multipart/form-data"
                        class="space-y-8"
                        x-data="{ selectedMethod: null, methodType: '' }"
                    >
                        @csrf

                        {{-- ── Payment method cards ── --}}
                        <div>
                            <p class="text-sm font-bold text-benizia-charcoal mb-4">
                                Active Payment Methods <span class="text-red-500">*</span>
                            </p>

                            @error('payment_method_id')
                                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach($paymentMethods as $method)
                                    <label
                                        class="cursor-pointer rounded-2xl bg-benizia-cream p-5 ring-1 ring-gray-200 has-[:checked]:ring-2 has-[:checked]:ring-benizia-green transition-all"
                                        @click="selectedMethod = {{ $method->id }}; methodType = '{{ $method->type }}'"
                                    >
                                        <input
                                            class="sr-only"
                                            type="radio"
                                            name="payment_method_id"
                                            value="{{ $method->id }}"
                                            {{ old('payment_method_id') == $method->id ? 'checked' : '' }}
                                        >

                                        <div class="flex items-start justify-between mb-3">
                                            <div class="w-10 h-10 rounded-xl bg-benizia-green/10 flex items-center justify-center">
                                                @if($method->type === 'Card Gateway')
                                                    <svg class="w-5 h-5 text-benizia-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                @else
                                                    <svg class="w-5 h-5 text-benizia-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                                @endif
                                            </div>
                                            <span class="text-xs font-bold uppercase tracking-widest text-benizia-gold">{{ $method->type }}</span>
                                        </div>

                                        <p class="font-semibold text-benizia-charcoal mb-1">{{ $method->name }}</p>

                                        @if($method->bank_name || $method->account_number)
                                            <div class="mt-3 space-y-1 text-xs text-gray-500 bg-white rounded-xl p-3">
                                                @if($method->bank_name)
                                                    <p><span class="font-medium text-benizia-charcoal">Bank:</span> {{ $method->bank_name }}</p>
                                                @endif
                                                @if($method->account_name)
                                                    <p><span class="font-medium text-benizia-charcoal">Account Name:</span> {{ $method->account_name }}</p>
                                                @endif
                                                @if($method->account_number)
                                                    <p><span class="font-medium text-benizia-charcoal">Account Number:</span> <strong class="text-benizia-charcoal text-sm">{{ $method->account_number }}</strong></p>
                                                @endif
                                            </div>
                                        @endif

                                        <p class="mt-3 text-xs leading-5 text-gray-500">{{ $method->instructions }}</p>

                                        {{-- Paystack pay-by-card button for Card Gateway methods --}}
                                        @if($method->provider === 'Paystack' || $method->type === 'Card Gateway')
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <form method="POST" action="{{ route('paystack.init', ['booking' => $booking->ref]) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-full bg-benizia-gold py-2.5 px-5 text-xs font-bold text-white transition hover:bg-benizia-charcoal"
                                                    >
                                                        Pay ₦{{ number_format($booking->commitment_fee) }} Online via Paystack
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ── Commitment fee banner ── --}}
                        <div class="rounded-2xl border-2 border-dashed border-benizia-gold/60 bg-benizia-gold/5 p-6">
                            <div class="flex items-center gap-2 text-benizia-gold mb-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs font-bold uppercase tracking-[0.2em]">Commitment Fee Required</span>
                            </div>
                            <p class="font-serif text-2xl text-benizia-charcoal mb-1">
                                Pay ₦{{ number_format($booking->commitment_fee) }} now
                            </p>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                This is {{ $booking->commitment_percent }}% of the booking total.
                                Balance due at check-in: <strong class="text-benizia-charcoal">₦{{ number_format($booking->balance_due) }}</strong>
                            </p>
                            <div class="mt-4 rounded-xl bg-white px-4 py-3 text-xs leading-5 text-gray-500">
                                <strong class="block text-benizia-charcoal mb-1">Policy</strong>
                                {{ $balanceNote }}
                            </div>
                        </div>

                        {{-- ── Proof of payment upload ── --}}
                        <div class="rounded-2xl bg-benizia-cream p-6">
                            <p class="text-sm font-bold text-benizia-charcoal mb-1">
                                Upload Proof of Payment
                                <span class="ml-1 text-xs font-normal text-gray-400">(optional — JPG, PNG, PDF, max 4 MB)</span>
                            </p>
                            <p class="text-xs text-gray-500 mb-4">
                                If you have already transferred the commitment fee, upload your receipt here. You can also send it by email later.
                            </p>

                            @error('proof')
                                <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            <input
                                type="file"
                                name="proof"
                                id="proof"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                                class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-benizia-green file:text-white hover:file:bg-benizia-charcoal transition"
                            >
                        </div>

                        {{-- ── Notes ── --}}
                        <div>
                            <label for="notes" class="block text-sm font-bold text-benizia-charcoal mb-2">
                                Special Requests
                                <span class="ml-1 text-xs font-normal text-gray-400">(optional)</span>
                            </label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Arrival time, airport transfer, dietary requirements, or other requests…"
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-benizia-charcoal placeholder-gray-400 focus:border-benizia-green focus:outline-none focus:ring-2 focus:ring-benizia-green/20 transition"
                            >{{ old('notes') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-full bg-benizia-green py-4 font-bold text-white text-base transition hover:bg-benizia-charcoal md:w-auto md:px-10"
                        >
                            Confirm Booking Request
                        </button>
                    </form>
                </div>

                {{-- ── RIGHT: Booking summary sidebar ──────────────────────────── --}}
                <aside class="lg:sticky lg:top-36 space-y-6">

                    {{-- Property image + name --}}
                    @if($booking->bookable && $booking->bookable->image)
                        <div class="overflow-hidden rounded-3xl aspect-[4/3]">
                            <img
                                src="{{ $booking->bookable->image }}"
                                alt="{{ $booking->bookable->name }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        </div>
                    @endif

                    <div class="rounded-3xl bg-benizia-cream p-6 space-y-5">
                        <div>
                            @if($booking->bookable)
                                <p class="text-xs font-semibold uppercase tracking-widest text-benizia-gold mb-1">
                                    {{ $booking->bookable->category ?? $booking->bookable->type ?? 'Accommodation' }}
                                </p>
                                <h2 class="font-serif text-2xl text-benizia-charcoal">{{ $booking->bookable->name }}</h2>
                            @endif
                        </div>

                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}
                            &rarr;
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}
                            &middot; {{ $booking->nights }} night{{ $booking->nights !== 1 ? 's' : '' }}
                            &middot; {{ $booking->guests }} guest{{ $booking->guests !== 1 ? 's' : '' }}
                        </p>

                        {{-- Price breakdown --}}
                        <div class="border-y border-gray-200 py-4 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Room rate × {{ $booking->nights }} night{{ $booking->nights !== 1 ? 's' : '' }}</span>
                                <strong class="text-benizia-charcoal">₦{{ number_format($booking->total) }}</strong>
                            </div>
                            <div class="flex justify-between text-benizia-green font-semibold">
                                <span>Pay now ({{ $booking->commitment_percent }}%)</span>
                                <span>₦{{ number_format($booking->commitment_fee) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Balance at check-in</span>
                                <span>₦{{ number_format($booking->balance_due) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-benizia-charcoal pt-1 border-t border-gray-100">
                                <span>Total</span>
                                <span class="font-serif text-lg text-benizia-green">₦{{ number_format($booking->total) }}</span>
                            </div>
                        </div>

                        {{-- Reference --}}
                        <div class="rounded-xl bg-white px-4 py-3">
                            <p class="text-xs text-gray-400 mb-1">Booking Reference</p>
                            <p class="font-serif text-xl font-bold text-benizia-charcoal tracking-wide">{{ $booking->ref }}</p>
                        </div>

                        {{-- Trust signals --}}
                        <ul class="space-y-2 text-xs text-gray-500 leading-5">
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 shrink-0 text-benizia-green mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Breakfast, pool &amp; gym access included
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 shrink-0 text-benizia-green mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Reservation team confirms within 2 hours
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 shrink-0 text-benizia-green mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Need help? Call {{ config('hotel.phone') }}
                            </li>
                        </ul>
                    </div>
                </aside>

            </div>
        </div>
    </section>

</x-layouts.app>
