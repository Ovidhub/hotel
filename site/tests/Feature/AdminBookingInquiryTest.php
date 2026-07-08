<?php
// tests/Feature/AdminBookingInquiryTest.php
use App\Models\BookingInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(fn () => $this->seed());

it('lets an admin view inquiries', function () {
    BookingInquiry::create(['name'=>'Ada','email'=>'a@e.com','room'=>'Classic Room','check_in'=>'x','check_out'=>'y','guests'=>'2']);
    $admin = User::where('is_admin', true)->first();
    $this->actingAs($admin)->get(route('admin.booking-inquiries.index'))
         ->assertOk()->assertSee('Ada');
});

it('forbids non-admins', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)->get(route('admin.booking-inquiries.index'))->assertForbidden();
});
