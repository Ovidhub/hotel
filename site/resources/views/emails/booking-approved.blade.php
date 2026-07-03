<x-mail::message>
# Your Booking is Approved ✅

Dear {{ $booking->guest_name }},

Good news — we have **verified your payment** and your reservation at **Hotel Benizia, Asaba** is now **confirmed**.

**Booking Reference:** {{ $booking->ref }}

<x-mail::panel>
**{{ $booking->bookable?->name }}**
Check-in: {{ $booking->check_in?->format('D, d M Y') }}
Check-out: {{ $booking->check_out?->format('D, d M Y') }}
Guests: {{ $booking->guests }} &nbsp;·&nbsp; Nights: {{ $booking->nights }}
</x-mail::panel>

<x-mail::table>
| Payment | Amount |
|:------------------------------|-------------------------:|
| Total | ₦{{ number_format($booking->total) }} |
| Commitment fee (paid) | ₦{{ number_format($booking->commitment_fee) }} |
| Balance due at check-in | ₦{{ number_format($booking->balance_due) }} |
</x-mail::table>

Please present this reference on arrival. The outstanding balance of **₦{{ number_format($booking->balance_due) }}** is payable at check-in.

We look forward to hosting you.

Warm regards,
**Hotel Benizia**
{{ config('hotel.phone') }} · {{ config('hotel.email') }}
{{ config('hotel.address') }}

<x-mail::subcopy>
If you have any questions about this booking, simply reply to this email or call us on {{ config('hotel.phone') }}.
</x-mail::subcopy>
</x-mail::message>
