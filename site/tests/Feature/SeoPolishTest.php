<?php

use App\Models\Room;
use App\Models\Apartment;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ══════════════════════════════════════════════════════════
//  Helper: assert exactly ONE <h1 in a response
// ══════════════════════════════════════════════════════════
function assertExactlyOneH1(TestResponse $response): void
{
    $html = $response->getContent();
    expect(substr_count($html, '<h1'))->toBe(1);
}

// ══════════════════════════════════════════════════════════
//  Helper: assert SEO meta tags are present
// ══════════════════════════════════════════════════════════
function assertSeoMeta(TestResponse $response): void
{
    $html = $response->getContent();
    // non-empty description
    expect($html)->toContain('name="description"');
    // canonical link
    expect($html)->toContain('rel="canonical"');
    // og:image
    expect($html)->toContain('property="og:image"');
}

// ══════════════════════════════════════════════════════════
//  1. HOME
// ══════════════════════════════════════════════════════════

test('home: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('home: images have alt text', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $html = $response->getContent();
    expect($html)->toContain('alt=');
});

// ══════════════════════════════════════════════════════════
//  2. ROOMS INDEX
// ══════════════════════════════════════════════════════════

test('rooms.index: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('rooms.index'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('rooms.index: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('rooms.index'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  3. ROOMS SHOW
// ══════════════════════════════════════════════════════════

test('rooms.show: exactly one h1, description, canonical, og:image', function () {
    $room = Room::first();
    $response = $this->get(route('rooms.show', $room));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('rooms.show: has breadcrumb JSON-LD', function () {
    $room = Room::first();
    $response = $this->get(route('rooms.show', $room));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  4. APARTMENTS INDEX
// ══════════════════════════════════════════════════════════

test('apartments.index: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('apartments.index'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('apartments.index: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('apartments.index'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  5. APARTMENTS SHOW
// ══════════════════════════════════════════════════════════

test('apartments.show: exactly one h1, description, canonical, og:image', function () {
    $apartment = Apartment::first();
    $response = $this->get(route('apartments.show', $apartment));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

// ══════════════════════════════════════════════════════════
//  6. BLOG INDEX
// ══════════════════════════════════════════════════════════

test('blog.index: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('blog.index'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('blog.index: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('blog.index'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  7. BLOG SHOW
// ══════════════════════════════════════════════════════════

test('blog.show: exactly one h1, description, canonical, og:image', function () {
    $post = BlogPost::first();
    $response = $this->get(route('blog.show', $post));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('blog.show: has breadcrumb JSON-LD', function () {
    $post = BlogPost::first();
    $response = $this->get(route('blog.show', $post));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  8. ABOUT
// ══════════════════════════════════════════════════════════

test('about: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('about'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('about: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('about'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  9. CONTACT
// ══════════════════════════════════════════════════════════

test('contact: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('contact'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('contact: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('contact'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  10. FAQ
// ══════════════════════════════════════════════════════════

test('faq: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('faq'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('faq: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('faq'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  11. RESTAURANT
// ══════════════════════════════════════════════════════════

test('restaurant: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('restaurant'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('restaurant: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('restaurant'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  12. GALLERY
// ══════════════════════════════════════════════════════════

test('gallery: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('gallery'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('gallery: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('gallery'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  13. EVENTS
// ══════════════════════════════════════════════════════════

test('events: exactly one h1, description, canonical, og:image', function () {
    $response = $this->get(route('events'));
    $response->assertOk();
    assertExactlyOneH1($response);
    assertSeoMeta($response);
});

test('events: has breadcrumb JSON-LD', function () {
    $response = $this->get(route('events'));
    $response->assertOk();
    $response->assertSee('BreadcrumbList', false);
});

// ══════════════════════════════════════════════════════════
//  14. LAYOUT: meta author tag
// ══════════════════════════════════════════════════════════

test('layout: has meta author Hotel Benizia', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('name="author"', false);
    $response->assertSee('Hotel Benizia', false);
});

// ══════════════════════════════════════════════════════════
//  15. CUSTOM 404
// ══════════════════════════════════════════════════════════

test('non-existent route returns 404 with branded message', function () {
    $response = $this->get('/this-route-does-not-exist-xyz');
    $response->assertNotFound();
    $response->assertSee('Page Not Found');
});

test('404 page contains link back to home', function () {
    $response = $this->get('/this-route-does-not-exist-xyz');
    $response->assertNotFound();
    $response->assertSee('Back to Home');
});
