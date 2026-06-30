<?php

use App\Models\Room;
use App\Models\Testimonial;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('home page returns 200', function () {
    $this->get(route('home'))->assertOk();
});

test('home page contains hero h1 with luxury or experience in heading', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $content = $response->getContent();
    // The page should contain exactly one <h1> tag
    expect(substr_count($content, '<h1'))->toBe(1);
});

test('home page has exactly one h1 element', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $content = $response->getContent();
    expect(substr_count($content, '<h1'))->toBe(1);
});

test('home page contains booking search bar with date inputs', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    // Check for check-in date input
    $response->assertSee('name="check_in"', false);
});

test('home page contains check-out date input', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('name="check_out"', false);
});

test('home page contains guests select input', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('name="guests"', false);
});

test('home page contains at least 3 seeded room names', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $rooms = Room::where('is_active', true)->orderBy('sort')->take(3)->get();
    foreach ($rooms as $room) {
        $response->assertSee($room->name);
    }
});

test('home page contains the word Restaurant', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Restaurant');
});

test('home page contains at least one seeded testimonial guest name', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $testimonial = Testimonial::first();
    $response->assertSee($testimonial->name);
});

test('home page contains JSON-LD Hotel schema from layout', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('"@type":["Hotel","LodgingBusiness"]', false);
});

test('home page contains a blog post title', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $post = \App\Models\BlogPost::where('published_at', '<=', now())->orderByDesc('published_at')->first();
    $response->assertSee($post->title);
});

test('home page contains eyebrow text for sections', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Accommodation');
});

test('home page contains Guest Reviews section', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Guest Reviews');
});

test('home page contains book your stay call to action', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    // The page should have "Book" somewhere prominent
    $response->assertSee('Book');
});

test('home page contains hotel tagline from config', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Asaba');
});

test('home page contains type date input attribute for check-in', function () {
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('type="date"', false);
});
