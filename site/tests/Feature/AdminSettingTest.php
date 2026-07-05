<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('admin can view the settings page', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->get(route('admin.settings'))
         ->assertOk()
         ->assertSee('WhatsApp');
});

test('non-admin gets 403 on the settings page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
         ->get(route('admin.settings'))
         ->assertForbidden();
});

test('admin can update the whatsapp number', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->put(route('admin.settings.update'), [
             'whatsapp_enabled' => '1',
             'whatsapp_number'  => '+234 801 234 5678',
             'whatsapp_message' => 'Hi there',
         ])
         ->assertSessionHas('status');

    expect(Setting::get('whatsapp_number'))->toBe('+234 801 234 5678');
    expect(Setting::get('whatsapp_enabled'))->toBe('1');
});

test('invalid whatsapp number is rejected', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->put(route('admin.settings.update'), [
             'whatsapp_number' => 'call me maybe',
         ])
         ->assertSessionHasErrors('whatsapp_number');
});

test('the floating whatsapp button renders on the home page when enabled', function () {
    Setting::put('whatsapp_enabled', '1');
    Setting::put('whatsapp_number', '+234 801 234 5678');

    $this->get(route('home'))
         ->assertOk()
         ->assertSee('wa.me/2348012345678', false);
});

test('the floating whatsapp button is hidden when disabled', function () {
    Setting::put('whatsapp_enabled', '0');
    Setting::put('whatsapp_number', '+234 801 234 5678');

    $this->get(route('home'))
         ->assertOk()
         ->assertDontSee('wa.me/2348012345678', false);
});
