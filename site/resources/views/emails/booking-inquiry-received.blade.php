{{-- resources/views/emails/booking-inquiry-received.blade.php --}}
<h2>New Booking Inquiry</h2>
<p><strong>Name:</strong> {{ $inquiry->name }}</p>
<p><strong>Email:</strong> {{ $inquiry->email }}</p>
<p><strong>Phone:</strong> {{ $inquiry->phone ?: '—' }}</p>
<p><strong>Room:</strong> {{ $inquiry->room }}</p>
<p><strong>Dates:</strong> {{ $inquiry->check_in }} → {{ $inquiry->check_out }}</p>
<p><strong>Guests:</strong> {{ $inquiry->guests }}</p>
<p><strong>Message:</strong><br>{{ $inquiry->message ?: '—' }}</p>
