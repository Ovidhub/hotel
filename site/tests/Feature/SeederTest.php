<?php

use App\Models\Room;
use App\Models\Apartment;
use App\Models\PaymentMethod;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds inventory and admin', function () {
    $this->seed();

    expect(Room::count())->toBe(5);
    expect(Apartment::count())->toBe(3);
    expect(User::where('is_admin', true)->count())->toBe(1);
    expect(PaymentMethod::count())->toBeGreaterThanOrEqual(3);
    expect(BlogPost::count())->toBeGreaterThanOrEqual(3);
    expect(Testimonial::count())->toBeGreaterThanOrEqual(5);
    expect(Faq::count())->toBeGreaterThanOrEqual(5);
});

it('seeds admin user with correct credentials', function () {
    $this->seed();

    $admin = User::where('email', 'admin@hotelbenizia.ng')->first();

    expect($admin)->not->toBeNull();
    expect($admin->is_admin)->toBeTrue();
    expect(\Illuminate\Support\Facades\Hash::check('password', $admin->password))->toBeTrue();
});

it('seeds rooms with required json fields', function () {
    $this->seed();

    $room = Room::where('slug', 'deluxe-classic')->first();

    expect($room)->not->toBeNull();
    expect($room->price)->toBe(30000);
    expect($room->amenities)->toBeArray();
    expect($room->includes)->toBeArray();
    expect($room->policies)->toBeArray();
    expect($room->gallery)->toBeArray();
    expect($room->best_for)->toBeArray();
});
