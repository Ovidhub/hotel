<?php

use App\Services\BookingCalculator;
use Carbon\Carbon;

// No DB needed — pure unit tests for the price calculator service.

test('calculates 3 nights correctly with 40% commitment', function () {
    $calculator = new BookingCalculator();
    $quote = $calculator->quote(
        price: 30000,
        checkIn: Carbon::parse('2026-03-01'),
        checkOut: Carbon::parse('2026-03-04'),
        commitmentPercent: 40
    );

    expect($quote['nights'])->toBe(3);
    expect($quote['total'])->toBe(90000);
    expect($quote['commitment_fee'])->toBe(36000);
    expect($quote['balance_due'])->toBe(54000);
});

test('same-day checkout clamps to at least 1 night', function () {
    $calculator = new BookingCalculator();
    $price = 50000;
    $quote = $calculator->quote(
        price: $price,
        checkIn: Carbon::parse('2026-05-10'),
        checkOut: Carbon::parse('2026-05-10'),
        commitmentPercent: 40
    );

    expect($quote['nights'])->toBe(1);
    expect($quote['total'])->toBe(50000);
    expect($quote['commitment_fee'])->toBe(20000);
    expect($quote['balance_due'])->toBe(30000);
});

test('calculates correctly with different commitment percent', function () {
    $calculator = new BookingCalculator();
    $quote = $calculator->quote(
        price: 20000,
        checkIn: Carbon::parse('2026-07-01'),
        checkOut: Carbon::parse('2026-07-03'),
        commitmentPercent: 50
    );

    expect($quote['nights'])->toBe(2);
    expect($quote['total'])->toBe(40000);
    expect($quote['commitment_fee'])->toBe(20000);
    expect($quote['balance_due'])->toBe(20000);
});

test('checkout before checkin clamps to 1 night', function () {
    $calculator = new BookingCalculator();
    $quote = $calculator->quote(
        price: 15000,
        checkIn: Carbon::parse('2026-06-05'),
        checkOut: Carbon::parse('2026-06-03'),
        commitmentPercent: 40
    );

    // diffInDays is negative, max(1, negative) = 1
    expect($quote['nights'])->toBe(1);
    expect($quote['total'])->toBe(15000);
});
