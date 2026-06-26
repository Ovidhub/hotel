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

// ── Static GET routes ──────────────────────────────────────────────────────────

test('home page returns 200', function () {
    $this->get(route('home'))->assertOk();
});

test('rooms index returns 200', function () {
    $this->get(route('rooms.index'))->assertOk();
});

test('apartments index returns 200', function () {
    $this->get(route('apartments.index'))->assertOk();
});

test('restaurant page returns 200', function () {
    $this->get(route('restaurant'))->assertOk();
});

test('gallery page returns 200', function () {
    $this->get(route('gallery'))->assertOk();
});

test('events page returns 200', function () {
    $this->get(route('events'))->assertOk();
});

test('blog index returns 200', function () {
    $this->get(route('blog.index'))->assertOk();
});

test('about page returns 200', function () {
    $this->get(route('about'))->assertOk();
});

test('contact page returns 200', function () {
    $this->get(route('contact'))->assertOk();
});

test('faq page returns 200', function () {
    $this->get(route('faq'))->assertOk();
});

// ── Slug-bound show routes ─────────────────────────────────────────────────────

test('rooms show returns 200 and shows room name', function () {
    $room = Room::first();
    $this->get(route('rooms.show', $room))
         ->assertOk()
         ->assertSee($room->name);
});

test('apartments show returns 200 and shows apartment name', function () {
    $apartment = Apartment::first();
    $this->get(route('apartments.show', $apartment))
         ->assertOk()
         ->assertSee($apartment->name);
});

test('blog show returns 200 and shows post title', function () {
    $post = BlogPost::first();
    $this->get(route('blog.show', $post))
         ->assertOk()
         ->assertSee($post->title);
});

// ── Data rendering ─────────────────────────────────────────────────────────────

test('rooms index renders seeded room cards', function () {
    $response = $this->get(route('rooms.index'));
    $response->assertOk();
    Room::where('is_active', true)->orderBy('sort')->each(function ($room) use ($response) {
        $response->assertSee($room->name);
    });
});

test('apartments index renders seeded apartment cards', function () {
    $response = $this->get(route('apartments.index'));
    $response->assertOk();
    Apartment::where('is_active', true)->orderBy('sort')->each(function ($apartment) use ($response) {
        $response->assertSee($apartment->name);
    });
});

test('blog index renders seeded blog post titles', function () {
    $response = $this->get(route('blog.index'));
    $response->assertOk();
    BlogPost::orderByDesc('published_at')->each(function ($post) use ($response) {
        $response->assertSee($post->title);
    });
});

test('faq page renders seeded faq questions', function () {
    $response = $this->get(route('faq'));
    $response->assertOk();
    Faq::orderBy('sort')->each(function ($faq) use ($response) {
        $response->assertSee($faq->question);
    });
});

test('rooms show displays room excerpt', function () {
    $room = Room::first();
    $this->get(route('rooms.show', $room))
         ->assertOk()
         ->assertSee($room->excerpt);
});

test('blog show displays post body', function () {
    $post = BlogPost::first();
    $this->get(route('blog.show', $post))
         ->assertOk()
         ->assertSee($post->title);
});
