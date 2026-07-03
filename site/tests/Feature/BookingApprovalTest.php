<?php

use App\Mail\BookingApproved;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function pendingBooking(): Booking
{
    $room = Room::first();

    return $room->bookings()->create([
        'ref'                => 'HB-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT),
        'guest_name'         => 'Ada Guest',
        'guest_email'        => 'ada@example.com',
        'guest_phone'        => '+2348000000000',
        'check_in'           => now()->addDays(5)->toDateString(),
        'check_out'          => now()->addDays(7)->toDateString(),
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 70000,
        'commitment_percent' => 40,
        'commitment_fee'     => 28000,
        'balance_due'        => 42000,
        'status'             => 'Pending Payment',
    ]);
}

test('admin approving a booking confirms it and emails the guest', function () {
    Mail::fake();
    $admin = User::where('is_admin', true)->first();
    $booking = pendingBooking();

    $this->actingAs($admin)
         ->post(route('admin.bookings.approve', $booking))
         ->assertRedirect(route('admin.bookings.show', $booking));

    $booking->refresh();
    expect($booking->status)->toBe('Confirmed');
    expect($booking->approved_at)->not->toBeNull();

    Mail::assertSent(BookingApproved::class, function ($mail) use ($booking) {
        return $mail->hasTo('ada@example.com') && $mail->booking->is($booking);
    });
});

test('admin rejecting a booking cancels it and sends no approval email', function () {
    Mail::fake();
    $admin = User::where('is_admin', true)->first();
    $booking = pendingBooking();

    $this->actingAs($admin)
         ->post(route('admin.bookings.reject', $booking))
         ->assertRedirect(route('admin.bookings.show', $booking));

    expect($booking->refresh()->status)->toBe('Cancelled');
    Mail::assertNothingSent();
});

test('approving is gated to admins', function () {
    Mail::fake();
    $booking = pendingBooking();

    // Guest (unauthenticated)
    $this->post(route('admin.bookings.approve', $booking))->assertRedirect(route('login'));

    // Non-admin
    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)->post(route('admin.bookings.approve', $booking))->assertForbidden();

    expect($booking->refresh()->status)->toBe('Pending Payment');
    Mail::assertNothingSent();
});
