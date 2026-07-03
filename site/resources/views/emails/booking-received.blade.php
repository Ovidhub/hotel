<x-mail::message>
# Thanks — We've Received Your Booking

Dear {{ $booking->guest_name }},

Thank you for choosing **Hotel Benizia, Asaba**. Your reservation has been received and is now **pending payment verification**.

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
| Commitment fee (due now) | ₦{{ number_format($booking->commitment_fee) }} |
| Balance due at check-in | ₦{{ number_format($booking->balance_due) }} |
</x-mail::table>

@if($hasProof)
We've received your **proof of payment**. Our team will verify the transfer shortly — you'll get a confirmation email once your booking is approved.
@else
To secure your booking, please transfer the **₦{{ number_format($booking->commitment_fee) }}** commitment fee and send us your proof of payment. Your booking is confirmed once we verify it.
@endif

Warm regards,
**Hotel Benizia**
{{ config('hotel.phone') }} · {{ config('hotel.email') }}

<x-mail::subcopy>
This is an acknowledgement, not a confirmation. You'll receive a separate approval email once your payment is verified. Questions? Reply to this email or call {{ config('hotel.phone') }}.
</x-mail::subcopy>
</x-mail::message>
