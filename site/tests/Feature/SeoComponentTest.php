<?php

use Illuminate\Support\Facades\Blade;

// ─── <x-seo /> ────────────────────────────────────────────────────────────────

test('seo component renders title with hotel suffix when title prop given', function () {
    $html = Blade::render('<x-seo title="Rooms" description="Our rooms" />');

    expect($html)->toContain('<title>Rooms — Hotel Benizia</title>');
});

test('seo component renders default title from tagline when no title given', function () {
    $html = Blade::render('<x-seo />');

    expect($html)->toContain('Hotel Benizia');
    expect($html)->toContain('Luxury in the heart of Asaba');
});

test('seo component renders meta description', function () {
    $html = Blade::render('<x-seo title="Rooms" description="Our rooms" />');

    expect($html)->toContain('name="description"');
    expect($html)->toContain('Our rooms');
});

test('seo component renders robots meta tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" />');

    expect($html)->toContain('name="robots"');
    expect($html)->toContain('index, follow');
});

test('seo component renders custom robots value', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" robots="noindex, nofollow" />');

    expect($html)->toContain('noindex, nofollow');
});

test('seo component renders canonical link tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" canonical="https://hotelbenizia.ng/rooms" />');

    expect($html)->toContain('rel="canonical"');
    expect($html)->toContain('https://hotelbenizia.ng/rooms');
});

test('seo component renders og title tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="Our rooms" />');

    expect($html)->toContain('og:title');
    expect($html)->toContain('Rooms');
});

test('seo component renders og description tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="Our rooms" />');

    expect($html)->toContain('og:description');
    expect($html)->toContain('Our rooms');
});

test('seo component renders og type tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" />');

    expect($html)->toContain('og:type');
    expect($html)->toContain('website');
});

test('seo component renders og site_name tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" />');

    expect($html)->toContain('og:site_name');
    expect($html)->toContain('Hotel Benizia');
});

test('seo component renders og image tag with default', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" />');

    expect($html)->toContain('og:image');
    expect($html)->toContain('hotelbenizia.ng');
});

test('seo component renders twitter card tag', function () {
    $html = Blade::render('<x-seo title="Rooms" description="d" />');

    expect($html)->toContain('twitter:card');
    expect($html)->toContain('summary_large_image');
});

test('seo component renders twitter title and description', function () {
    $html = Blade::render('<x-seo title="My Title" description="My desc" />');

    expect($html)->toContain('twitter:title');
    expect($html)->toContain('My Title');
    expect($html)->toContain('twitter:description');
    expect($html)->toContain('My desc');
});

test('seo component renders twitter image', function () {
    $html = Blade::render('<x-seo title="T" description="d" />');

    expect($html)->toContain('twitter:image');
});

// ─── <x-schema.hotel /> ───────────────────────────────────────────────────────

test('hotel schema emits json-ld script tag', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('<script type="application/ld+json">');
});

test('hotel schema contains type Hotel', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('"@type":"Hotel"');
});

test('hotel schema contains address locality Asaba', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('"addressLocality":"Asaba"');
});

test('hotel schema contains hotel name', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('"name":"Hotel Benizia"');
});

test('hotel schema contains telephone', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('+2348134062487');
});

test('hotel schema contains price range', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('NGN 30,000');
});

test('hotel schema contains aggregate rating', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('AggregateRating');
    expect($html)->toContain('4.9');
});

test('hotel schema contains amenity features', function () {
    $html = Blade::render('<x-schema.hotel />');

    expect($html)->toContain('LocationFeatureSpecification');
});

// ─── <x-schema.breadcrumb /> ─────────────────────────────────────────────────

test('breadcrumb schema emits json-ld script tag', function () {
    $items = [['name' => 'Home', 'url' => 'https://hotelbenizia.ng'], ['name' => 'Rooms', 'url' => 'https://hotelbenizia.ng/rooms']];
    $html = Blade::render('<x-schema.breadcrumb :items="$items" />', ['items' => $items]);

    expect($html)->toContain('<script type="application/ld+json">');
});

test('breadcrumb schema contains BreadcrumbList type', function () {
    $items = [['name' => 'Home', 'url' => 'https://hotelbenizia.ng'], ['name' => 'Rooms', 'url' => 'https://hotelbenizia.ng/rooms']];
    $html = Blade::render('<x-schema.breadcrumb :items="$items" />', ['items' => $items]);

    expect($html)->toContain('"@type":"BreadcrumbList"');
});

test('breadcrumb schema contains item names and urls', function () {
    $items = [['name' => 'Home', 'url' => 'https://hotelbenizia.ng'], ['name' => 'Rooms', 'url' => 'https://hotelbenizia.ng/rooms']];
    $html = Blade::render('<x-schema.breadcrumb :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Home');
    expect($html)->toContain('Rooms');
    expect($html)->toContain('https://hotelbenizia.ng/rooms');
});

test('breadcrumb schema contains position numbers', function () {
    $items = [['name' => 'Home', 'url' => 'https://hotelbenizia.ng'], ['name' => 'Rooms', 'url' => 'https://hotelbenizia.ng/rooms']];
    $html = Blade::render('<x-schema.breadcrumb :items="$items" />', ['items' => $items]);

    expect($html)->toContain('"position":1');
    expect($html)->toContain('"position":2');
});

// ─── <x-schema.product /> ────────────────────────────────────────────────────

test('product schema emits json-ld script tag', function () {
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" />',
    );

    expect($html)->toContain('<script type="application/ld+json">');
});

test('product schema contains Product type', function () {
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" />',
    );

    expect($html)->toContain('"@type":"Product"');
});

test('product schema contains offer with NGN currency', function () {
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" />',
    );

    expect($html)->toContain('"priceCurrency":"NGN"');
    expect($html)->toContain('"price":30000');
});

test('product schema contains InStock availability', function () {
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" />',
    );

    expect($html)->toContain('InStock');
});

test('product schema includes aggregate rating when provided', function () {
    $rating = ['ratingValue' => 4.9, 'reviewCount' => 50];
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" :rating="$rating" />',
        ['rating' => $rating],
    );

    expect($html)->toContain('AggregateRating');
    expect($html)->toContain('4.9');
});

test('product schema has no aggregate rating when rating not provided', function () {
    $html = Blade::render(
        '<x-schema.product name="Deluxe Classic" description="A room" image="https://example.com/img.jpg" price="30000" url="https://hotelbenizia.ng/rooms/deluxe-classic" />',
    );

    expect($html)->not->toContain('AggregateRating');
});

// ─── <x-schema.article /> ────────────────────────────────────────────────────

test('article schema emits json-ld script tag', function () {
    $html = Blade::render(
        '<x-schema.article title="Blog Post" description="desc" image="https://example.com/img.jpg" author="Hotel Benizia" datePublished="2026-01-08" url="https://hotelbenizia.ng/blog/test" />',
    );

    expect($html)->toContain('<script type="application/ld+json">');
});

test('article schema contains BlogPosting type', function () {
    $html = Blade::render(
        '<x-schema.article title="Blog Post" description="desc" image="https://example.com/img.jpg" author="Hotel Benizia" datePublished="2026-01-08" url="https://hotelbenizia.ng/blog/test" />',
    );

    expect($html)->toContain('"@type":"BlogPosting"');
});

test('article schema contains title and description', function () {
    $html = Blade::render(
        '<x-schema.article title="Blog Post" description="desc" image="https://example.com/img.jpg" author="Hotel Benizia" datePublished="2026-01-08" url="https://hotelbenizia.ng/blog/test" />',
    );

    expect($html)->toContain('Blog Post');
    expect($html)->toContain('desc');
});

test('article schema contains author and datePublished', function () {
    $html = Blade::render(
        '<x-schema.article title="Blog Post" description="desc" image="https://example.com/img.jpg" author="Hotel Benizia" datePublished="2026-01-08" url="https://hotelbenizia.ng/blog/test" />',
    );

    expect($html)->toContain('Hotel Benizia');
    expect($html)->toContain('2026-01-08');
});

// ─── <x-schema.faq /> ────────────────────────────────────────────────────────

test('faq schema emits json-ld script tag', function () {
    $faqs = [['question' => 'Q1', 'answer' => 'A1']];
    $html = Blade::render('<x-schema.faq :faqs="$faqs" />', ['faqs' => $faqs]);

    expect($html)->toContain('<script type="application/ld+json">');
});

test('faq schema contains FAQPage type', function () {
    $faqs = [['question' => 'Q1', 'answer' => 'A1']];
    $html = Blade::render('<x-schema.faq :faqs="$faqs" />', ['faqs' => $faqs]);

    expect($html)->toContain('"@type":"FAQPage"');
});

test('faq schema contains question and answer', function () {
    $faqs = [['question' => 'Q1', 'answer' => 'A1']];
    $html = Blade::render('<x-schema.faq :faqs="$faqs" />', ['faqs' => $faqs]);

    expect($html)->toContain('Q1');
    expect($html)->toContain('A1');
});

test('faq schema contains Question and Answer types', function () {
    $faqs = [['question' => 'Q1', 'answer' => 'A1'], ['question' => 'Q2', 'answer' => 'A2']];
    $html = Blade::render('<x-schema.faq :faqs="$faqs" />', ['faqs' => $faqs]);

    expect($html)->toContain('"@type":"Question"');
    expect($html)->toContain('"@type":"Answer"');
});

test('faq schema handles multiple faqs', function () {
    $faqs = [
        ['question' => 'What time is check-in?', 'answer' => 'From 2:00 PM'],
        ['question' => 'Is breakfast included?', 'answer' => 'Yes, all rooms include breakfast'],
    ];
    $html = Blade::render('<x-schema.faq :faqs="$faqs" />', ['faqs' => $faqs]);

    expect($html)->toContain('What time is check-in?');
    expect($html)->toContain('Is breakfast included?');
});
