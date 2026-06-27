<?php
use function Pest\Laravel\get;
it('loads the welcome route', function () { get('/')->assertStatus(200); });
it('exposes hotel config', function () { expect(config('hotel.name'))->toBe('Hotel Benizia'); });
