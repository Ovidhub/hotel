<?php

use App\Models\Room;
use App\Models\Apartment;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ─── sitemap.xml ─────────────────────────────────────────────────────────────

test('sitemap returns 200 with xml content type', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('xml');
});

test('sitemap contains urlset root element', function () {
    $response = $this->get('/sitemap.xml');

    $content = $response->getContent();
    expect($content)->toContain('<urlset');
    expect($content)->toContain('</urlset>');
});

test('sitemap is valid xml', function () {
    $response = $this->get('/sitemap.xml');

    $xml = simplexml_load_string($response->getContent());
    expect($xml)->not->toBeFalse();
});

test('sitemap contains canonical host', function () {
    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->toContain('hotelbenizia.ng');
});

test('sitemap contains home url', function () {
    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->toContain('hotelbenizia.ng/');
});

test('sitemap contains seeded room slug url', function () {
    $room = Room::where('is_active', true)->first();
    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->toContain($room->slug);
});

test('sitemap contains seeded blog post slug url', function () {
    $post = BlogPost::whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->first();

    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->toContain($post->slug);
});

test('sitemap does not contain admin urls', function () {
    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->not->toContain('/admin');
});

test('sitemap does not contain checkout or booking urls', function () {
    $response = $this->get('/sitemap.xml');

    $content = $response->getContent();
    expect($content)->not->toContain('/checkout');
    expect($content)->not->toContain('/book/');
});

test('sitemap contains only active rooms', function () {
    // Deactivate a room and verify it is excluded
    $room = Room::first();
    $room->update(['is_active' => false]);

    $response = $this->get('/sitemap.xml');

    // The deactivated room slug should not appear in URLs
    expect($response->getContent())->not->toContain('rooms/' . $room->slug);
});

test('sitemap contains lastmod elements', function () {
    $response = $this->get('/sitemap.xml');

    expect($response->getContent())->toContain('<lastmod>');
});

test('sitemap contains priority and changefreq elements', function () {
    $response = $this->get('/sitemap.xml');

    $content = $response->getContent();
    expect($content)->toContain('<priority>');
    expect($content)->toContain('<changefreq>');
});

test('sitemap contains static pages', function () {
    $response = $this->get('/sitemap.xml');

    $content = $response->getContent();
    expect($content)->toContain('/restaurant');
    expect($content)->toContain('/gallery');
    expect($content)->toContain('/about');
    expect($content)->toContain('/contact');
    expect($content)->toContain('/blog');
});

// ─── robots.txt ───────────────────────────────────────────────────────────────

test('robots txt returns 200', function () {
    $response = $this->get('/robots.txt');

    $response->assertStatus(200);
});

test('robots txt contains sitemap reference with canonical host', function () {
    $response = $this->get('/robots.txt');

    $content = $response->getContent();
    expect($content)->toContain('Sitemap:');
    expect($content)->toContain('hotelbenizia.ng/sitemap.xml');
});

test('robots txt disallows admin path', function () {
    $response = $this->get('/robots.txt');

    expect($response->getContent())->toContain('Disallow: /admin');
});

test('robots txt disallows transactional paths', function () {
    $response = $this->get('/robots.txt');

    $content = $response->getContent();
    expect($content)->toContain('Disallow: /checkout');
    expect($content)->toContain('Disallow: /book');
});

test('robots txt allows all crawling', function () {
    $response = $this->get('/robots.txt');

    expect($response->getContent())->toContain('Allow: /');
});
