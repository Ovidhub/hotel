<x-layouts.app
    :title="'Booking Confirmed — ' . $booking->ref"
    :description="'Your booking request has been received by Hotel Benizia. Reference: ' . $booking->ref . '. Our team will confirm availability and payment details shortly.'"
    robots="noindex, follow">

    <section class="min-h-screen py-20 px-4 bg-benizia-cream flex items-center justify-center">
        <div class="mx-auto max-w-3xl w-full">

            {{-- Success card --}}
            <div class="rounded-[2rem] bg-white p-8 md:p-12 shadow-[0_30px_80px_rgba(23,81,77,0.10)]">

                {{-- Icon + label --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-benizia-green flex items-center justify-center shadow-lg mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-benizia-gold mb-3">Booking Request Sent</p>

                    <h1 class="font-serif text-4xl md:text-5xl text-benizia-charcoal leading-tight mb-4">
                        Thank you, {{ $booking->guest_name }}
                    </h1>

                    <p class="text-sm leading-7 text-gray-500 max-w-lg">
                        Your reservation request for
                        <strong class="text-benizia-charcoal">{{ $booking->bookable?->name ?? 'your selected accommodation' }}</strong>
                        has been received. Our reservations team will confirm availability, payment, and check-in details shortly.
                    </p>
                </div>

                {{-- Booking details grid --}}
                <div class="mt-10 rounded-2xl bg-benizia-cream p-5 grid gap-3 text-sm md:grid-cols-2">
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Booking Reference</span>
                        <strong class="font-serif text-xl text-benizia-charcoal tracking-wide">{{ $booking->ref }}</strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Total Stay</span>
                        <strong class="text-benizia-charcoal">₦{{ number_format($booking->total) }}</strong>
                    </div>
                    @if($booking->bookable)
                        <div>
                            <span class="block text-xs text-gray-400 mb-0.5">Accommodation</span>
                            <strong class="text-benizia-charcoal">{{ $booking->bookable->name }}</strong>
                        </div>
                    @endif
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Stay Dates</span>
                        <strong class="text-benizia-charcoal">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                            &rarr;
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                        </strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Commitment Fee (Pay Now)</span>
                        <strong class="text-benizia-green">₦{{ number_format($booking->commitment_fee) }}</strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Balance at Check-in</span>
                        <strong class="text-benizia-charcoal">₦{{ number_format($booking->balance_due) }}</strong>
                    </div>
                    @if($booking->paymentMethod)
                        <div class="md:col-span-2">
                            <span class="block text-xs text-gray-400 mb-0.5">Payment Method</span>
                            <strong class="text-benizia-charcoal">{{ $booking->paymentMethod->name }}</strong>
                        </div>
                    @endif
                </div>

                {{-- Payment method details / next steps --}}
                @if($booking->paymentMethod)
                    <div class="mt-6 rounded-2xl border border-benizia-gold/40 bg-benizia-gold/5 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-benizia-gold mb-3">Next Steps</p>

                        @if($booking->paymentMethod->type === 'Bank Transfer' && $booking->paymentMethod->account_number)
                            <div class="space-y-1 text-sm mb-3">
                                <p><span class="text-gray-500">Bank:</span> <strong class="text-benizia-charcoal">{{ $booking->paymentMethod->bank_name }}</strong></p>
                                <p><span class="text-gray-500">Account Name:</span> <strong class="text-benizia-charcoal">{{ $booking->paymentMethod->account_name }}</strong></p>
                                <p><span class="text-gray-500">Account Number:</span> <strong class="font-serif text-lg text-benizia-charcoal">{{ $booking->paymentMethod->account_number }}</strong></p>
                                <p><span class="text-gray-500">Amount:</span> <strong class="text-benizia-green">₦{{ number_format($booking->commitment_fee) }}</strong></p>
                            </div>
                        @endif

                        <p class="text-sm text-gray-600 leading-relaxed">{{ $booking->paymentMethod->instructions }}</p>
                    </div>
                @endif

                {{-- Booking policy note --}}
                <div class="mt-6 rounded-xl bg-gray-50 border border-gray-100 px-4 py-3 text-xs text-gray-500 leading-5">
                    <strong class="block text-benizia-charcoal mb-1">Booking Policy</strong>
                    {{ $balanceNote }}
                    Free cancellation up to {{ config('hotel.booking.cancellation_hours') }} hours before check-in.
                </div>

                {{-- CTA buttons --}}
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                        href="{{ route('rooms.index') }}"
                        class="rounded-full bg-benizia-green px-8 py-4 text-sm font-bold uppercase tracking-[0.18em] text-white text-center transition hover:bg-benizia-charcoal"
                    >
                        Browse More Rooms
                    </a>
                    <a
                        href="{{ route('home') }}"
                        class="rounded-full border border-benizia-green px-8 py-4 text-sm font-bold uppercase tracking-[0.18em] text-benizia-green text-center transition hover:bg-benizia-green hover:text-white"
                    >
                        Return Home
                    </a>
                </div>

                {{-- Contact --}}
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4 text-sm text-gray-500">
                    <a href="tel:{{ config('hotel.phone_href') }}" class="flex items-center justify-center gap-2 hover:text-benizia-green transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ config('hotel.phone') }}
                    </a>
                    <a href="mailto:{{ config('hotel.email') }}" class="flex items-center justify-center gap-2 hover:text-benizia-green transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ config('hotel.email') }}
                    </a>
                </div>

            </div>
        </div>
    </section>

</x-layouts.app>
