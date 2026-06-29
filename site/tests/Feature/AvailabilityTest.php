<?php

use App\Models\Room;
use App\Models\User;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function makeBooking(Room $room, string $in, string $out, string $status = 'Confirmed'): void
{
    $room->bookings()->create([
        'ref'                => 'HB-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT),
        'guest_name'         => 'Test Guest',
        'guest_email'        => 'guest@example.com',
        'guest_phone'        => '+2348000000000',
        'check_in'           => $in,
        'check_out'          => $out,
        'nights'             => 1,
        'guests'             => 1,
        'total'              => 30000,
        'commitment_percent' => 40,
        'commitment_fee'     => 12000,
        'balance_due'        => 18000,
        'status'             => $status,
    ]);
}

// ─── AvailabilityService ──────────────────────────────────────────────────────

test('a range is available when there are no bookings or blocks', function () {
    $svc = app(AvailabilityService::class);
    $room = Room::first();
    $room->update(['units' => 1]);

    expect($svc->isRangeAvailable($room, Carbon::parse('2030-01-10'), Carbon::parse('2030-01-13')))->toBeTrue();
});

test('an admin block makes the range unavailable', function () {
    $svc = app(AvailabilityService::class);
    $room = Room::first();
    $room->update(['units' => 1]);

    $room->availabilityBlocks()->create([
        'start_date' => '2030-01-11',
        'end_date'   => '2030-01-11',
        'source'     => 'manual',
    ]);

    expect($svc->isRangeAvailable($room, Carbon::parse('2030-01-10'), Carbon::parse('2030-01-13')))->toBeFalse();
});

test('a single-unit room is unavailable once booked, available with more units', function () {
    $svc = app(AvailabilityService::class);
    $room = Room::first();
    $room->update(['units' => 1]);

    makeBooking($room, '2030-02-01', '2030-02-05', 'Confirmed');

    // Overlapping range is now full.
    expect($svc->isRangeAvailable($room, Carbon::parse('2030-02-02'), Carbon::parse('2030-02-04')))->toBeFalse();

    // With 2 units, the same range is available again.
    $room->update(['units' => 2]);
    expect($svc->isRangeAvailable($room, Carbon::parse('2030-02-02'), Carbon::parse('2030-02-04')))->toBeTrue();
});

test('cancelled bookings do not occupy availability', function () {
    $svc = app(AvailabilityService::class);
    $room = Room::first();
    $room->update(['units' => 1]);

    makeBooking($room, '2030-03-01', '2030-03-05', 'Cancelled');

    expect($svc->isRangeAvailable($room, Carbon::parse('2030-03-02'), Carbon::parse('2030-03-04')))->toBeTrue();
});

test('the check-out day is free for the next guest', function () {
    $svc = app(AvailabilityService::class);
    $room = Room::first();
    $room->update(['units' => 1]);

    makeBooking($room, '2030-04-01', '2030-04-05', 'Confirmed'); // occupies nights 1-4

    // A stay starting on the 5th (the previous check-out day) is fine.
    expect($svc->isRangeAvailable($room, Carbon::parse('2030-04-05'), Carbon::parse('2030-04-07')))->toBeTrue();
});

// ─── Booking flow rejects unavailable dates ───────────────────────────────────

test('booking store rejects dates that are blocked', function () {
    $room = Room::first();
    $room->update(['units' => 1]);
    $room->availabilityBlocks()->create([
        'start_date' => '2030-05-10',
        'end_date'   => '2030-05-20',
        'source'     => 'manual',
    ]);

    $response = $this->post(route('booking.store'), [
        'type'        => 'room',
        'slug'        => $room->slug,
        'check_in'    => '2030-05-11',
        'check_out'   => '2030-05-13',
        'guests'      => 2,
        'guest_name'  => 'Jane Guest',
        'guest_email' => 'jane@example.com',
        'guest_phone' => '+2348000000000',
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('bookings', ['guest_email' => 'jane@example.com']);
});

test('booking store accepts available dates', function () {
    $room = Room::first();
    $room->update(['units' => 1]);

    $this->post(route('booking.store'), [
        'type'        => 'room',
        'slug'        => $room->slug,
        'check_in'    => '2030-06-01',
        'check_out'   => '2030-06-03',
        'guests'      => 2,
        'guest_name'  => 'Open Guest',
        'guest_email' => 'open@example.com',
        'guest_phone' => '+2348000000000',
    ])->assertRedirect();

    $this->assertDatabaseHas('bookings', ['guest_email' => 'open@example.com']);
});

// ─── Admin availability management ────────────────────────────────────────────

test('admin can block and unblock dates for a room', function () {
    $admin = User::where('is_admin', true)->first();
    $room = Room::first();

    // Create a block
    $this->actingAs($admin)->post(route('admin.availability.blocks.store', ['type' => 'room', 'id' => $room->id]), [
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date'   => now()->addDays(8)->toDateString(),
        'reason'     => 'Maintenance',
    ])->assertRedirect();

    $this->assertDatabaseHas('availability_blocks', [
        'bookable_type' => Room::class,
        'bookable_id'   => $room->id,
        'reason'        => 'Maintenance',
        'source'        => 'manual',
    ]);

    $block = $room->availabilityBlocks()->first();

    // Remove it
    $this->actingAs($admin)->delete(route('admin.availability.blocks.destroy', $block))->assertRedirect();
    $this->assertDatabaseMissing('availability_blocks', ['id' => $block->id]);
});

test('availability pages are gated to admins', function () {
    $this->get(route('admin.availability.index'))->assertRedirect(route('login'));

    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)->get(route('admin.availability.index'))->assertForbidden();

    $admin = User::where('is_admin', true)->first();
    $this->actingAs($admin)->get(route('admin.availability.index'))->assertOk();
});

test('units can be set on a room and limit availability', function () {
    $admin = User::where('is_admin', true)->first();
    $room = Room::first();

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), [
        'name'        => $room->name,
        'category'    => $room->category,
        'price'       => $room->price,
        'size'        => $room->size,
        'rating'      => $room->rating,
        'guests'      => $room->guests,
        'beds'        => $room->beds,
        'excerpt'     => $room->excerpt,
        'description' => $room->description,
        'gallery'     => implode("\n", $room->gallery ?? []),
        'units'       => 3,
        'is_active'   => 1,
    ])->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->units)->toBe(3);
});
