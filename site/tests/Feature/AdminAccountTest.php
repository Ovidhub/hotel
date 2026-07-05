<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('admin can view the account page', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->get(route('admin.account'))
         ->assertOk()
         ->assertSee('Change Password');
});

test('non-admin gets 403 on the account page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
         ->get(route('admin.account'))
         ->assertForbidden();
});

test('admin can change password with the correct current password', function () {
    $admin = User::where('is_admin', true)->first();
    $admin->update(['password' => Hash::make('password')]);

    $this->actingAs($admin)
         ->put(route('admin.account.password'), [
             'current_password'      => 'password',
             'password'              => 'new-strong-password-123',
             'password_confirmation' => 'new-strong-password-123',
         ])
         ->assertSessionHas('status');

    expect(Hash::check('new-strong-password-123', $admin->fresh()->password))->toBeTrue();
});

test('password change fails with the wrong current password', function () {
    $admin = User::where('is_admin', true)->first();
    $admin->update(['password' => Hash::make('password')]);

    $this->actingAs($admin)
         ->put(route('admin.account.password'), [
             'current_password'      => 'wrong-password',
             'password'              => 'new-strong-password-123',
             'password_confirmation' => 'new-strong-password-123',
         ])
         ->assertSessionHasErrors('current_password');

    expect(Hash::check('password', $admin->fresh()->password))->toBeTrue();
});
