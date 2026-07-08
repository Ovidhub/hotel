<?php
// tests/Feature/BlacktowerThemeTest.php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    config()->set('hotel.theme', 'blacktower');
});

it('renders the black tower home with a seeded room and hero copy', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('Experience Comfort'); // hero heading from page.tsx
    $response->assertSee(\App\Models\Room::first()->name);
});
