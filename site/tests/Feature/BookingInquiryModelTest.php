<?php
// tests/Feature/BookingInquiryModelTest.php
use App\Models\BookingInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('persists a booking inquiry with defaults', function () {
    $i = BookingInquiry::create([
        'name' => 'Ada', 'email' => 'ada@example.com', 'phone' => '0803',
        'room' => 'Classic Room', 'check_in' => '2026-08-01',
        'check_out' => '2026-08-03', 'guests' => '2', 'message' => 'hi',
    ]);
    expect($i->status)->toBe('new');
    $this->assertDatabaseHas('booking_inquiries', ['email' => 'ada@example.com']);
});
