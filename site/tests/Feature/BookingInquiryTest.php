<?php
// tests/Feature/BookingInquiryTest.php
use App\Mail\BookingInquiryReceived;
use App\Models\BookingInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('stores a valid inquiry, mails the hotel, and redirects with status', function () {
    Mail::fake();
    $payload = [
        'name' => 'Ada', 'email' => 'ada@example.com', 'phone' => '0803',
        'room' => 'Classic Room', 'check_in' => '2026-08-01',
        'check_out' => '2026-08-03', 'guests' => '2', 'message' => 'hi',
    ];
    $this->post(route('booking-inquiry.store'), $payload)
         ->assertRedirect()
         ->assertSessionHas('status');
    $this->assertDatabaseHas('booking_inquiries', ['email' => 'ada@example.com']);
    Mail::assertSent(BookingInquiryReceived::class);
});

it('rejects an invalid inquiry', function () {
    $this->post(route('booking-inquiry.store'), ['name' => ''])
         ->assertSessionHasErrors(['name', 'email', 'room', 'check_in', 'check_out', 'guests']);
    expect(BookingInquiry::count())->toBe(0);
});
