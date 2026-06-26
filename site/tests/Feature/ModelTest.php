<?php

use App\Models\Room;
use App\Models\Booking;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('casts room json columns and formats price and returns slug route key', function () {
    $room = Room::create([
        'slug'        => 'deluxe-suite',
        'name'        => 'Deluxe Suite',
        'category'    => 'Suite',
        'price'       => 30000,
        'price_label' => 'per night',
        'size'        => 'Large',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.5,
        'reviews'     => 10,
        'excerpt'     => 'A beautiful suite.',
        'description' => 'Full description here.',
        'image'       => 'room.jpg',
        'gallery'     => [],
        'amenities'   => ['WiFi'],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    expect($room->amenities)->toBeArray()->toContain('WiFi');
    expect($room->price_formatted)->toBe('₦30,000');
    expect($room->getRouteKeyName())->toBe('slug');
});

it('booking has morphTo bookable and belongsTo paymentMethod relationships', function () {
    $pm = PaymentMethod::create([
        'name'         => 'Bank Transfer',
        'type'         => 'bank',
        'provider'     => 'GTBank',
        'instructions' => 'Transfer to account.',
    ]);

    $room = Room::create([
        'slug'        => 'standard-room',
        'name'        => 'Standard Room',
        'category'    => 'Standard',
        'price'       => 20000,
        'price_label' => 'per night',
        'size'        => 'Medium',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.0,
        'reviews'     => 5,
        'excerpt'     => 'Nice room.',
        'description' => 'Description.',
        'image'       => 'room2.jpg',
        'gallery'     => [],
        'amenities'   => [],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $booking = Booking::create([
        'ref'                => 'BK-001',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'John Doe',
        'guest_email'        => 'john@example.com',
        'guest_phone'        => '08012345678',
        'check_in'           => '2026-07-01',
        'check_out'          => '2026-07-03',
        'nights'             => 2,
        'guests'             => 2,
        'total'              => 40000,
        'commitment_percent' => 30,
        'commitment_fee'     => 12000,
        'balance_due'        => 28000,
        'status'             => 'Pending Payment',
        'payment_method_id'  => $pm->id,
    ]);

    expect($booking->bookable)->toBeInstanceOf(Room::class);
    expect($booking->paymentMethod)->toBeInstanceOf(PaymentMethod::class);
    expect($booking->check_in)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
