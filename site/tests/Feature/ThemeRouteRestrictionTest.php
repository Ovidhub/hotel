<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->seed());

test('default theme (Benizia) serves all public routes', function () {
    // theme is 'default' by default
    $this->get(route('restaurant'))->assertOk();
    $this->get(route('apartments.index'))->assertOk();
    $this->get(route('gallery'))->assertOk();
    $this->get(route('faq'))->assertOk();
});

test('a non-default theme 404s the routes it does not implement', function () {
    config()->set('hotel.theme', 'blacktower');

    $this->get(route('restaurant'))->assertNotFound();
    $this->get(route('apartments.index'))->assertNotFound();
    $this->get(route('gallery'))->assertNotFound();
    $this->get(route('events'))->assertNotFound();
    $this->get(route('blog.index'))->assertNotFound();
    $this->get(route('faq'))->assertNotFound();
});

test('a non-default theme still serves its own pages', function () {
    config()->set('hotel.theme', 'blacktower');

    $this->get('/')->assertOk();                 // home
    $this->get(route('rooms.index'))->assertOk();
    $this->get(route('about'))->assertOk();
    $this->get(route('contact'))->assertOk();
});
