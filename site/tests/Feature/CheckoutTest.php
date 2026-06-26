<?php

use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Models\Room;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ─────────────────────────────────────────────────────────────────────────────
// 1. Valid booking POST creates a DB record and redirects to checkout.show
// ─────────────────────────────────────────────────────────────────────────────
it('creates a booking and redirects to checkout when payload is valid', function () {
    $room      = Room::first();
    $checkIn   = now()->addDay()->format('Y-m-d');
    $checkOut  = now()->addDays(3)->format('Y-m-d');

    $response = $this->post(route('booking.store'), [
        'type'        => 'room',
        'slug'        => $room->slug,
        'check_in'    => $checkIn,
        'check_out'   => $checkOut,
        'guests'      => 2,
        'guest_name'  => 'Ada Okonkwo',
        'guest_email' => 'ada@example.com',
        'guest_phone' => '+234 800 000 0001',
    ]);

    // Should redirect to checkout.show (not back, not success)
    $response->assertRedirectContains('/checkout/');

    // DB should contain exactly one booking
    $this->assertDatabaseCount('bookings', 1);

    $booking = Booking::first();

    // Ref format
    expect($booking->ref)->toMatch('/^HB-\d{5}$/');

    // Status
    expect($booking->status)->toBe('Pending Payment');

    // Commitment fee is 40% of total
    $nights   = 2; // 3 days - 1 day = 2 nights
    $total    = $room->price * $nights;
    $expected = (int) round($total * 40 / 100);
    expect($booking->commitment_fee)->toBe($expected);

    // Balance = total - fee
    expect($booking->balance_due)->toBe($total - $expected);

    // Commitment percent stored
    expect($booking->commitment_percent)->toBe(40);
});

// ─────────────────────────────────────────────────────────────────────────────
// 2. Invalid booking (check_out before check_in) → redirect back with errors
// ─────────────────────────────────────────────────────────────────────────────
it('rejects booking when check_out is before check_in', function () {
    $room = Room::first();

    $response = $this->post(route('booking.store'), [
        'type'        => 'room',
        'slug'        => $room->slug,
        'check_in'    => now()->addDays(3)->format('Y-m-d'),
        'check_out'   => now()->addDay()->format('Y-m-d'),  // BEFORE check_in
        'guests'      => 2,
        'guest_name'  => 'Test Guest',
        'guest_email' => 'test@example.com',
        'guest_phone' => '+234 800 000 0002',
    ]);

    $response->assertSessionHasErrors(['check_out']);
    $this->assertDatabaseCount('bookings', 0);
});

// ─────────────────────────────────────────────────────────────────────────────
// 3. checkout.show renders the booking summary and payment methods
// ─────────────────────────────────────────────────────────────────────────────
it('renders checkout page with booking ref and payment methods', function () {
    $room    = Room::first();
    $booking = Booking::create([
        'ref'                => 'HB-12345',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Ada Okonkwo',
        'guest_email'        => 'ada@example.com',
        'guest_phone'        => '+234 800 000 0001',
        'check_in'           => now()->addDay(),
        'check_out'          => now()->addDays(3),
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 60000,
        'commitment_percent' => 40,
        'commitment_fee'     => 24000,
        'balance_due'        => 36000,
        'status'             => 'Pending Payment',
    ]);

    $response = $this->get(route('checkout.show', ['booking' => $booking->ref]));

    $response->assertStatus(200);
    $response->assertSee('HB-12345');
    // Bank transfer details should be visible (from seeder)
    $response->assertSee('Zenith Bank');
    // Commitment fee should appear
    $response->assertSee('24');  // ₦24,000 or similar
});

// ─────────────────────────────────────────────────────────────────────────────
// 4. checkout.confirm updates payment method + stores proof → redirects to success
// ─────────────────────────────────────────────────────────────────────────────
it('updates booking payment method and proof on checkout confirm', function () {
    Storage::fake('public');

    $room    = Room::first();
    $booking = Booking::create([
        'ref'                => 'HB-99999',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Ada Okonkwo',
        'guest_email'        => 'ada@example.com',
        'guest_phone'        => '+234 800 000 0001',
        'check_in'           => now()->addDay(),
        'check_out'          => now()->addDays(3),
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 60000,
        'commitment_percent' => 40,
        'commitment_fee'     => 24000,
        'balance_due'        => 36000,
        'status'             => 'Pending Payment',
    ]);

    $paymentMethod = PaymentMethod::where('active', true)->first();
    $fakeFile      = UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf');

    $response = $this->post(route('checkout.confirm', ['booking' => $booking->ref]), [
        'payment_method_id' => $paymentMethod->id,
        'proof'             => $fakeFile,
    ]);

    $response->assertRedirect(route('booking.success', ['booking' => $booking->ref]));

    $booking->refresh();
    expect($booking->payment_method_id)->toBe($paymentMethod->id);
    expect($booking->proof_path)->not->toBeNull();

    Storage::disk('public')->assertExists($booking->proof_path);
});

// ─────────────────────────────────────────────────────────────────────────────
// 5. booking.success page shows ref, total, and commitment fee
// ─────────────────────────────────────────────────────────────────────────────
it('renders booking success page with ref and totals', function () {
    $room          = Room::first();
    $paymentMethod = PaymentMethod::where('active', true)->first();

    $booking = Booking::create([
        'ref'                => 'HB-54321',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Ada Okonkwo',
        'guest_email'        => 'ada@example.com',
        'guest_phone'        => '+234 800 000 0001',
        'check_in'           => now()->addDay(),
        'check_out'          => now()->addDays(3),
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 60000,
        'commitment_percent' => 40,
        'commitment_fee'     => 24000,
        'balance_due'        => 36000,
        'status'             => 'Pending Payment',
        'payment_method_id'  => $paymentMethod->id,
    ]);

    $response = $this->get(route('booking.success', ['booking' => $booking->ref]));

    $response->assertStatus(200);
    $response->assertSee('HB-54321');
    // Total should appear somewhere in the page
    $response->assertSee('60');  // Part of 60,000
});
