<?php

use App\Models\Room;
use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ── booking.create GET route ──────────────────────────────────────────────────

test('booking form for a room returns 200 and shows room details', function () {
    $room = Room::first();

    $this->get(route('booking.create', ['type' => 'room', 'slug' => $room->slug]))
         ->assertOk()
         ->assertSee($room->name);
});

test('booking form shows price per night', function () {
    $room = Room::first();

    $this->get(route('booking.create', ['type' => 'room', 'slug' => $room->slug]))
         ->assertOk()
         ->assertSee('₦');
});

test('booking form shows commitment policy text', function () {
    $room = Room::first();

    $this->get(route('booking.create', ['type' => 'room', 'slug' => $room->slug]))
         ->assertOk()
         ->assertSee('40');  // commitment_percent
});

test('booking form contains required input fields', function () {
    $room = Room::first();

    $response = $this->get(route('booking.create', ['type' => 'room', 'slug' => $room->slug]));
    $response->assertOk()
             ->assertSee('check_in', false)
             ->assertSee('check_out', false)
             ->assertSee('guest_name', false)
             ->assertSee('guests', false)
             ->assertSee('guest_email', false)
             ->assertSee('guest_phone', false);
});

test('booking form for an apartment returns 200 and shows apartment details', function () {
    $apartment = Apartment::first();

    $this->get(route('booking.create', ['type' => 'apartment', 'slug' => $apartment->slug]))
         ->assertOk()
         ->assertSee($apartment->name);
});

test('invalid type returns 404', function () {
    $this->get(route('booking.create', ['type' => 'villa', 'slug' => 'any-slug']))
         ->assertNotFound();
});

test('invalid room slug returns 404', function () {
    $this->get(route('booking.create', ['type' => 'room', 'slug' => 'nonexistent-room-zzz']))
         ->assertNotFound();
});

test('invalid apartment slug returns 404', function () {
    $this->get(route('booking.create', ['type' => 'apartment', 'slug' => 'nonexistent-apt-zzz']))
         ->assertNotFound();
});
