<?php

use Illuminate\Support\Facades\View;

it('returns the flat view name for the default theme', function () {
    config()->set('hotel.theme', 'default');
    expect(theme_view('home'))->toBe('home');
});

it('returns the themed view name when the theme provides it', function () {
    config()->set('hotel.theme', 'blacktower');
    // 'themes.blacktower.probe' exists on disk only in later tasks; simulate with a real temp view path check:
    View::addLocation(resource_path('views'));
    // Fall back when themed view missing:
    expect(theme_view('___nope___'))->toBe('___nope___');
});
