<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('guest is redirected to login when accessing admin dashboard', function () {
    $this->get(route('admin.dashboard'))
         ->assertRedirect(route('login'));
});

test('non-admin user gets 403 when accessing admin dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
         ->get(route('admin.dashboard'))
         ->assertForbidden();
});

test('admin user can access dashboard and sees stat labels', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->get(route('admin.dashboard'))
         ->assertOk()
         ->assertSee('Dashboard')
         ->assertSee('Revenue');
});
