{{-- HB Apartments booking contact — separate from the hotel line. --}}
<div class="rounded-3xl bg-benizia-cream p-8 text-center ring-1 ring-benizia-gold/20">
    <p class="text-xs font-bold uppercase tracking-[0.15em] text-benizia-gold">HB Apartments — Booking Contact</p>
    <p class="mx-auto mt-2 max-w-xl text-sm text-gray-600">
        For serviced-apartment reservations and enquiries, contact HB Apartments directly:
    </p>
    <div class="mt-5 flex flex-col items-center justify-center gap-4 sm:flex-row sm:gap-8">
        <a href="tel:{{ config('hotel.apartments.phone_href') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-benizia-green transition hover:text-benizia-gold">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
            </svg>
            {{ config('hotel.apartments.phone') }}
        </a>
        <a href="mailto:{{ config('hotel.apartments.email') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-benizia-green transition hover:text-benizia-gold">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
            {{ config('hotel.apartments.email') }}
        </a>
    </div>
</div>
