<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);
it('has core tables with key columns', function () {
  expect(Schema::hasColumns('rooms', ['slug','name','category','price','amenities','gallery']))->toBeTrue();
  expect(Schema::hasColumns('apartments', ['slug','status','amenities']))->toBeTrue();
  expect(Schema::hasColumns('bookings', ['ref','bookable_type','bookable_id','commitment_fee','status']))->toBeTrue();
  expect(Schema::hasColumns('payment_methods', ['name','type','accepts_commitment_fee']))->toBeTrue();
  expect(Schema::hasColumn('users','is_admin'))->toBeTrue();
});
