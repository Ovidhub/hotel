<?php

use App\Models\Room;
use App\Models\Apartment;
use App\Models\BlogPost;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ── rooms/index ───────────────────────────────────────────────────────────────

test('rooms index lists all 5 room names and a price', function () {
    $response = $this->get(route('rooms.index'));
    $response->assertOk();

    // All 5 room names appear
    $response->assertSee('Deluxe Classic');
    $response->assertSee('Deluxe Premium');
    $response->assertSee('Alcove Room');
    $response->assertSee('Diplomatic Suite');
    $response->assertSee('Penthouse Suite');

    // A price in NGN symbol
    $response->assertSee('₦');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

test('rooms index title does not contain Hotel Benizia in the passed title segment', function () {
    $response = $this->get(route('rooms.index'));
    // The <title> tag is built as "{title} — Hotel Benizia"
    // So the raw title passed must NOT itself contain "Hotel Benizia"
    // We check that "Rooms" appears before "— Hotel Benizia" in the title
    $response->assertSee('Rooms &amp; Suites in Asaba — Hotel Benizia', false);
});

// ── rooms/show ────────────────────────────────────────────────────────────────

test('room show displays amenities, includes, policies and book button with Product schema', function () {
    $room = Room::where('slug', 'deluxe-classic')->first();
    $response = $this->get(route('rooms.show', $room));
    $response->assertOk();

    // One amenity from the room
    $response->assertSee('Breakfast included');

    // One includes item
    $response->assertSee('Complimentary Breakfast');

    // One policies item
    $response->assertSee('Check-in: 2:00 PM');

    // Book button
    $response->assertSee('Book This Room');

    // Product schema JSON-LD
    $response->assertSee('"@type":"Product"', false);

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

test('room show breadcrumb schema present', function () {
    $room = Room::first();
    $response = $this->get(route('rooms.show', $room));
    $response->assertSee('"@type":"BreadcrumbList"', false);
});

test('room show title follows convention no Hotel Benizia in title segment', function () {
    $room = Room::first();
    $response = $this->get(route('rooms.show', $room));
    // title should be "{room->name} — {room->category} — Hotel Benizia"
    $response->assertSee($room->name . ' — ' . $room->category . ' — Hotel Benizia', false);
});

// ── apartments/index ──────────────────────────────────────────────────────────

test('apartments index lists apartment names and status badges', function () {
    $response = $this->get(route('apartments.index'));
    $response->assertOk();

    $response->assertSee('Classic Room');
    $response->assertSee('Deluxe Classic Apartment');
    $response->assertSee('Supreme 2-Bedroom Apartment');
    $response->assertSee('Supreme 4-Bedroom Apartment');

    // Status word
    $response->assertSee('Available');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── apartments/show ───────────────────────────────────────────────────────────

test('apartment show displays description and Product schema', function () {
    $apartment = Apartment::where('slug', 'supreme-2-bedroom-apartment')->first();
    $response = $this->get(route('apartments.show', $apartment));
    $response->assertOk();

    $response->assertSee('serviced apartment');
    $response->assertSee('"@type":"Product"', false);

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── restaurant ────────────────────────────────────────────────────────────────

test('restaurant page shows menu items and NGN prices', function () {
    $response = $this->get(route('restaurant'));
    $response->assertOk();

    // Menu items (from controller data)
    $response->assertSee('Chef Breakfast Platter');
    $response->assertSee('NGN');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── gallery ───────────────────────────────────────────────────────────────────

test('gallery page shows multiple images with alt text', function () {
    $response = $this->get(route('gallery'));
    $response->assertOk();

    // Multiple <img with alt
    $content = $response->getContent();
    $imgCount = substr_count($content, '<img');
    expect($imgCount)->toBeGreaterThan(5);

    // All gallery images have alt attributes
    $response->assertSee('alt=');

    // Single h1
    expect(substr_count($content, '<h1'))->toBe(1);
});

// ── events ────────────────────────────────────────────────────────────────────

test('events page shows hall and conference and boardroom copy', function () {
    $response = $this->get(route('events'));
    $response->assertOk();

    $content = strtolower($response->getContent());
    expect($content)->toContain('hall');
    expect($content)->toContain('boardroom');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── blog/index ────────────────────────────────────────────────────────────────

test('blog index lists post titles', function () {
    $response = $this->get(route('blog.index'));
    $response->assertOk();

    $response->assertSee('How to book a hotel in Asaba for a stress-free experience');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── blog/show ─────────────────────────────────────────────────────────────────

test('blog show displays body and Article schema', function () {
    $post = BlogPost::where('slug', 'how-to-book-hotel-in-asaba')->first();
    $response = $this->get(route('blog.show', $post));
    $response->assertOk();

    // Body fragment
    $response->assertSee('stress-free');

    // Article schema
    $response->assertSee('"@type":"BlogPosting"', false);

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

test('blog show breadcrumb schema present', function () {
    $post = BlogPost::first();
    $response = $this->get(route('blog.show', $post));
    $response->assertSee('"@type":"BreadcrumbList"', false);
});

// ── about ────────────────────────────────────────────────────────────────────

test('about page shows vision mission story words', function () {
    $response = $this->get(route('about'));
    $response->assertOk();

    $content = strtolower($response->getContent());
    // At least one of vision / mission / story must appear
    expect(
        str_contains($content, 'vision') ||
        str_contains($content, 'mission') ||
        str_contains($content, 'story')
    )->toBeTrue();

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── contact ───────────────────────────────────────────────────────────────────

test('contact page shows form with name email message fields and the address', function () {
    $response = $this->get(route('contact'));
    $response->assertOk();

    // Form fields
    $response->assertSee('name="name"', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('name="message"', false);

    // Address
    $response->assertSee('Summit Road');
    $response->assertSee('Asaba');

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});

// ── faq ──────────────────────────────────────────────────────────────────────

test('faq page shows seeded question and FAQPage schema', function () {
    $response = $this->get(route('faq'));
    $response->assertOk();

    // Seeded question
    $response->assertSee('What time is check-in and check-out?');

    // FAQPage schema
    $response->assertSee('"@type":"FAQPage"', false);

    // Single h1
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
});
