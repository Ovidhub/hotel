<?php

use Illuminate\Support\Facades\Route;

beforeEach(function () {
    // Register a temporary test-only route for the shell test
    Route::get('/__shelltest', function () {
        return view('__shelltest');
    })->name('__shelltest');
});

test('public layout shell renders 200 with hotel name', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('Hotel Benizia');
});

test('public layout shell renders phone number', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('+234 813 406 2487');
});

test('public layout shell renders email address', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('booking@hotelbenizia.ng');
});

test('public layout shell renders nav label HB Apartments', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('HB Apartments');
});

test('public layout shell renders nav label Home', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('Home');
});

test('public layout shell renders footer copyright year', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee(date('Y'));
});

test('public layout shell renders slot body content', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('Body Content Here');
});

test('public layout shell renders json-ld hotel schema', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('"@type":["Hotel","LodgingBusiness"]', false);
});

test('public layout shell renders amenity ticker items', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('Swimming Pool');
});

test('public layout shell renders Book Now CTA', function () {
    $response = $this->get('/__shelltest');

    $response->assertStatus(200);
    $response->assertSee('Book Now');
});
