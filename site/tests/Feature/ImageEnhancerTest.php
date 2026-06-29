<?php

use App\Models\Room;
use App\Models\User;
use App\Services\ImageEnhancer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    Storage::fake('public');
});

test('it downsizes large images and stores optimized WebP', function () {
    $file = UploadedFile::fake()->image('big.jpg', 3000, 2000);

    $path = app(ImageEnhancer::class)->enhanceAndStore($file, 'rooms');

    expect($path)->toEndWith('.webp')->toStartWith('rooms/');
    Storage::disk('public')->assertExists($path);

    $info = getimagesizefromstring(Storage::disk('public')->get($path));
    expect($info[0])->toBeLessThanOrEqual(ImageEnhancer::MAX_EDGE); // width capped
    expect($info[1])->toBeLessThanOrEqual(ImageEnhancer::MAX_EDGE); // height capped
    expect($info['mime'])->toBe('image/webp');
});

test('it keeps small images small (no upscaling)', function () {
    $file = UploadedFile::fake()->image('small.jpg', 800, 600);

    $path = app(ImageEnhancer::class)->enhanceAndStore($file, 'rooms');

    $info = getimagesizefromstring(Storage::disk('public')->get($path));
    expect($info[0])->toBe(800);
    expect($info[1])->toBe(600);
});

test('uploaded room photos are enhanced to WebP through the admin', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)->post(route('admin.rooms.store'), [
        'name'          => 'Crisp Room',
        'category'      => 'Suite',
        'price'         => 60000,
        'size'          => 'Large',
        'rating'        => 4.9,
        'guests'        => 2,
        'beds'          => 1,
        'excerpt'       => 'Sharp photos.',
        'description'   => 'A room whose photos are auto-enhanced.',
        'image_file'    => UploadedFile::fake()->image('main.jpg', 2400, 1600),
        'gallery_files' => [UploadedFile::fake()->image('g1.jpg', 2000, 1500)],
        'is_active'     => 1,
    ])->assertRedirect(route('admin.rooms.index'));

    $room = Room::where('name', 'Crisp Room')->first();
    expect($room->image)->toEndWith('.webp');
    Storage::disk('public')->assertExists($room->image);
    foreach ($room->gallery as $path) {
        expect($path)->toEndWith('.webp');
        Storage::disk('public')->assertExists($path);
    }
});
