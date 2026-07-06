<?php

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    Storage::fake('public');
});

/** Base payload for a room update. */
function roomPayload(Room $room, array $overrides = []): array
{
    return array_merge([
        'name'        => $room->name,
        'category'    => $room->category,
        'price'       => $room->price,
        'guests'      => $room->guests,
        'beds'        => $room->beds,
        'excerpt'     => $room->excerpt,
        'description' => $room->description,
        'is_active'   => 1,
    ], $overrides);
}

test('gallery_order reorders existing gallery images', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp', 'rooms/c.webp']]);

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        'gallery_order' => json_encode(['rooms/c.webp', 'rooms/a.webp', 'rooms/b.webp']),
    ]))->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe(['rooms/c.webp', 'rooms/a.webp', 'rooms/b.webp']);
});

test('gallery_order removes images that are left out of the list', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp', 'rooms/c.webp']]);

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        'gallery_order' => json_encode(['rooms/a.webp', 'rooms/c.webp']),
    ]))->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe(['rooms/a.webp', 'rooms/c.webp']);
});

test('an empty gallery_order clears the gallery', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp']]);

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        'gallery_order' => json_encode([]),
    ]))->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe([]);
});

test('an absent gallery_order preserves the existing gallery (no data loss)', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp']]);

    // No gallery_order and no gallery field at all (e.g. the JS never ran).
    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room))
         ->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe(['rooms/a.webp', 'rooms/b.webp']);
});

test('an empty-string gallery_order preserves the existing gallery (JS failed)', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp']]);

    // An empty string (not "[]") means the JS binding never populated it.
    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        'gallery_order' => '',
    ]))->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe(['rooms/a.webp', 'rooms/b.webp']);
});

test('gallery_order interleaves a new upload at a chosen position', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $room->update(['gallery' => ['rooms/a.webp', 'rooms/b.webp']]);

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        // new upload placed between a and b
        'gallery_order' => json_encode(['rooms/a.webp', '__new__:0', 'rooms/b.webp']),
        'gallery_files' => [UploadedFile::fake()->image('new.jpg')],
    ]))->assertRedirect(route('admin.rooms.index'));

    $gallery = $room->fresh()->gallery;
    expect($gallery)->toHaveCount(3);
    expect($gallery[0])->toBe('rooms/a.webp');
    expect($gallery[1])->toStartWith('rooms/');
    expect($gallery[2])->toBe('rooms/b.webp');
    Storage::disk('public')->assertExists($gallery[1]);
});

test('legacy gallery field still works when gallery_order is absent', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), roomPayload($room, [
        'gallery' => "rooms/x.webp\nrooms/y.webp",
    ]))->assertRedirect(route('admin.rooms.index'));

    expect($room->fresh()->gallery)->toBe(['rooms/x.webp', 'rooms/y.webp']);
});
