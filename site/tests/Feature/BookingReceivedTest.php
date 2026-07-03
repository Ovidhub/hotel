<?php

use App\Mail\BookingReceived;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    Storage::fake('public');
});

function unconfirmedBooking(): Booking
{
    return Room::first()->bookings()->create([
        'ref'                => 'HB-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT),
        'guest_name'         => 'Uche Guest',
        'guest_email'        => 'uche@example.com',
        'guest_phone'        => '+2348000000000',
        'check_in'           => now()->addDays(4)->toDateString(),
        'check_out'          => now()->addDays(6)->toDateString(),
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 70000,
        'commitment_percent' => 40,
        'commitment_fee'     => 28000,
        'balance_due'        => 42000,
        'status'             => 'Pending Payment',
    ]);
}

test('confirming checkout emails the guest a payment-received acknowledgement', function () {
    Mail::fake();
    $booking = unconfirmedBooking();
    $method = PaymentMethod::where('active', true)->first();

    $this->post(route('checkout.confirm', ['booking' => $booking->ref]), [
        'payment_method_id' => $method->id,
        'proof'             => UploadedFile::fake()->create('receipt.pdf', 120, 'application/pdf'),
    ])->assertRedirect(route('booking.success', ['booking' => $booking->ref]));

    Mail::assertSent(BookingReceived::class, fn ($mail) => $mail->hasTo('uche@example.com') && $mail->hasProof === true);
});
