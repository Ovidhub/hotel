<?php

use App\Models\Booking;
use App\Models\Room;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    // Create a test booking directly
    $room = Room::first();

    $this->booking = Booking::create([
        'ref'                => 'HB-PAYSK',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Test Guest',
        'guest_email'        => 'testguest@example.com',
        'guest_phone'        => '+234 800 000 0099',
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

    // Ensure no Paystack keys are configured — force the no-keys path
    config(['services.paystack.secret' => null]);
});

// ─────────────────────────────────────────────────────────────────────────────
// 1. Paystack config defaults are correct
// ─────────────────────────────────────────────────────────────────────────────
it('has the correct paystack base_url default in config', function () {
    // Even without secret, the base_url default should be the Paystack API URL
    $baseUrl = config('services.paystack.base_url');
    expect($baseUrl)->toBe('https://api.paystack.co');
});

// ─────────────────────────────────────────────────────────────────────────────
// 2. paystack.init with no keys: redirects back with a status flash message
// ─────────────────────────────────────────────────────────────────────────────
it('redirects back with not-configured flash when Paystack keys are absent', function () {
    $response = $this->post(route('paystack.init', ['booking' => $this->booking->ref]));

    // Must redirect (back to checkout.show)
    $response->assertRedirect(route('checkout.show', ['booking' => $this->booking->ref]));

    // Must flash a 'status' session message
    $response->assertSessionHas('status');

    // Flash message must mention "not configured"
    $flashMessage = session('status');
    expect(strtolower($flashMessage))->toContain('not configured');
});

// ─────────────────────────────────────────────────────────────────────────────
// 3. Booking status remains unchanged when Paystack init is called with no keys
// ─────────────────────────────────────────────────────────────────────────────
it('does not change booking status when Paystack keys are absent', function () {
    $this->post(route('paystack.init', ['booking' => $this->booking->ref]));

    $this->booking->refresh();
    expect($this->booking->status)->toBe('Pending Payment');
});

// ─────────────────────────────────────────────────────────────────────────────
// 4. paystack.callback with no keys: redirects without error (graceful)
// ─────────────────────────────────────────────────────────────────────────────
it('handles paystack callback gracefully when keys are absent', function () {
    $response = $this->get(route('paystack.callback', ['reference' => 'fake-reference-123']));

    // Must redirect (no exception, no 500)
    $response->assertRedirect();
});

// ─────────────────────────────────────────────────────────────────────────────
// 5. paystack.callback with no reference: redirects without error
// ─────────────────────────────────────────────────────────────────────────────
it('handles paystack callback with no reference gracefully', function () {
    $response = $this->get(route('paystack.callback'));

    $response->assertRedirect();
});
