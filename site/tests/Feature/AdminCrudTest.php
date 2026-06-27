<?php

use App\Models\User;
use App\Models\Room;
use App\Models\Apartment;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    Storage::fake('public');
});

// ─── Middleware protection tests ──────────────────────────────────────────────

test('guest is redirected to login when accessing admin rooms index', function () {
    $this->get(route('admin.rooms.index'))
         ->assertRedirect(route('login'));
});

test('non-admin gets 403 when accessing admin rooms index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
         ->get(route('admin.rooms.index'))
         ->assertForbidden();
});

// ─── Rooms CRUD ───────────────────────────────────────────────────────────────

test('admin can create a room via POST and is redirected with DB row', function () {
    $admin = User::where('is_admin', true)->first();

    $response = $this->actingAs($admin)->post(route('admin.rooms.store'), [
        'name'        => 'Deluxe King Suite',
        'category'    => 'Suite',
        'price'       => 45000,
        'size'        => 'Large',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.5,
        'reviews'     => 10,
        'excerpt'     => 'A lovely suite.',
        'description' => 'Full description of the deluxe king suite.',
        'image_file'  => UploadedFile::fake()->image('room.jpg'),
        'amenities'   => "WiFi\nAC\nTV",
        'gallery'     => '',
        'includes'    => '',
        'policies'    => '',
        'best_for'    => '',
        'is_active'   => 1,
        'sort'        => 0,
    ]);

    $response->assertRedirect(route('admin.rooms.index'));
    $this->assertDatabaseHas('rooms', ['name' => 'Deluxe King Suite']);

    $room = Room::where('name', 'Deluxe King Suite')->first();
    expect($room->amenities)->toContain('WiFi');
    expect($room->amenities)->toContain('AC');
    expect($room->price_label)->toBe('NGN 45,000');

    // The uploaded image was stored on the public disk and the path saved.
    expect($room->image)->toStartWith('rooms/');
    Storage::disk('public')->assertExists($room->image);
    expect($room->imageUrl())->toContain('/storage/rooms/');
});

test('admin can upload gallery photos which appear in the room gallery', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)->post(route('admin.rooms.store'), [
        'name'          => 'Gallery Room',
        'category'      => 'Suite',
        'price'         => 50000,
        'size'          => 'Large',
        'guests'        => 2,
        'beds'          => 1,
        'rating'        => 4.8,
        'excerpt'       => 'Room with photos.',
        'description'   => 'A room with several uploaded photos.',
        'image_file'    => UploadedFile::fake()->image('main.jpg'),
        'gallery_files' => [
            UploadedFile::fake()->image('g1.jpg'),
            UploadedFile::fake()->image('g2.jpg'),
        ],
        'is_active'     => 1,
    ])->assertRedirect(route('admin.rooms.index'));

    $room = Room::where('name', 'Gallery Room')->first();
    expect($room->gallery)->toHaveCount(2);
    foreach ($room->gallery as $path) {
        Storage::disk('public')->assertExists($path);
    }
    // galleryUrls() includes the main image first, then the two gallery photos.
    expect($room->galleryUrls())->toHaveCount(3);
});

test('updating a room without a new file keeps the existing image', function () {
    $admin = User::where('is_admin', true)->first();
    $room = Room::first();
    $originalImage = $room->image;

    $this->actingAs($admin)->put(route('admin.rooms.update', $room), [
        'name'        => $room->name,
        'category'    => $room->category,
        'price'       => 99000,
        'size'        => $room->size,
        'rating'      => $room->rating,
        'guests'      => $room->guests,
        'beds'        => $room->beds,
        'excerpt'     => $room->excerpt,
        'description' => $room->description,
        'amenities'   => implode("\n", $room->amenities ?? []),
        'gallery'     => implode("\n", $room->gallery ?? []),
        'is_active'   => 1,
    ])->assertRedirect(route('admin.rooms.index'));

    $room->refresh();
    expect($room->price)->toBe(99000);
    expect($room->image)->toBe($originalImage); // unchanged
});

test('admin rooms index returns 200 and lists rooms', function () {
    $admin = User::where('is_admin', true)->first();

    // Create a room first
    Room::create([
        'name'        => 'Standard Room',
        'slug'        => 'standard-room',
        'category'    => 'Standard',
        'price'       => 20000,
        'price_label' => 'NGN 20,000',
        'size'        => 'Medium',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.0,
        'reviews'     => 5,
        'excerpt'     => 'A standard room.',
        'description' => 'Standard room description.',
        'image'       => 'https://example.com/img.jpg',
        'gallery'     => [],
        'amenities'   => ['WiFi'],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $this->actingAs($admin)
         ->get(route('admin.rooms.index'))
         ->assertOk()
         ->assertSee('Standard Room');
});

test('admin can access room edit page', function () {
    $admin = User::where('is_admin', true)->first();

    $room = Room::create([
        'name'        => 'Edit Room',
        'slug'        => 'edit-room',
        'category'    => 'Standard',
        'price'       => 25000,
        'price_label' => 'NGN 25,000',
        'size'        => 'Small',
        'guests'      => 1,
        'beds'        => 1,
        'rating'      => 4.0,
        'reviews'     => 3,
        'excerpt'     => 'Short excerpt.',
        'description' => 'Description.',
        'image'       => 'https://example.com/img.jpg',
        'gallery'     => [],
        'amenities'   => ['WiFi'],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $this->actingAs($admin)
         ->get(route('admin.rooms.edit', $room))
         ->assertOk()
         ->assertSee('Edit Room');
});

test('admin can update a room price and price_label is updated', function () {
    $admin = User::where('is_admin', true)->first();

    $room = Room::create([
        'name'        => 'Update Room',
        'slug'        => 'update-room',
        'category'    => 'Standard',
        'price'       => 30000,
        'price_label' => 'NGN 30,000',
        'size'        => 'Medium',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.0,
        'reviews'     => 5,
        'excerpt'     => 'Excerpt.',
        'description' => 'Description.',
        'image'       => 'https://example.com/img.jpg',
        'gallery'     => [],
        'amenities'   => ['WiFi'],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.rooms.update', $room), [
        'name'        => 'Update Room',
        'category'    => 'Standard',
        'price'       => 50000,
        'size'        => 'Medium',
        'guests'      => 2,
        'beds'        => 1,
        'rating'      => 4.0,
        'reviews'     => 5,
        'excerpt'     => 'Excerpt.',
        'description' => 'Description.',
        'image'       => 'https://example.com/img.jpg',
        'amenities'   => 'WiFi',
        'gallery'     => '',
        'includes'    => '',
        'policies'    => '',
        'best_for'    => '',
        'is_active'   => 1,
        'sort'        => 0,
    ]);

    $response->assertRedirect(route('admin.rooms.index'));
    $room->refresh();
    expect($room->price)->toBe(50000);
    expect($room->price_label)->toBe('NGN 50,000');
});

test('admin can delete a room', function () {
    $admin = User::where('is_admin', true)->first();

    $room = Room::create([
        'name'        => 'Delete Room',
        'slug'        => 'delete-room',
        'category'    => 'Standard',
        'price'       => 10000,
        'price_label' => 'NGN 10,000',
        'size'        => 'Small',
        'guests'      => 1,
        'beds'        => 1,
        'rating'      => 3.5,
        'reviews'     => 2,
        'excerpt'     => 'Excerpt.',
        'description' => 'Description.',
        'image'       => 'https://example.com/img.jpg',
        'gallery'     => [],
        'amenities'   => ['WiFi'],
        'includes'    => [],
        'policies'    => [],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.rooms.destroy', $room))
         ->assertRedirect(route('admin.rooms.index'));

    $this->assertDatabaseMissing('rooms', ['name' => 'Delete Room']);
});

// ─── Apartments CRUD ──────────────────────────────────────────────────────────

test('admin can create an apartment via POST', function () {
    $admin = User::where('is_admin', true)->first();

    $response = $this->actingAs($admin)->post(route('admin.apartments.store'), [
        'name'        => 'Garden Apartment',
        'type'        => 'Studio',
        'price'       => 60000,
        'status'      => 'Available',
        'image_file'  => UploadedFile::fake()->image('apt.jpg'),
        'bedrooms'    => 1,
        'bathrooms'   => 1,
        'occupancy'   => 2,
        'description' => 'Garden-facing studio.',
        'amenities'   => "WiFi\nPool",
        'gallery'     => '',
        'is_active'   => 1,
        'sort'        => 0,
    ]);

    $response->assertRedirect(route('admin.apartments.index'));
    $this->assertDatabaseHas('apartments', ['name' => 'Garden Apartment']);

    $apt = Apartment::where('name', 'Garden Apartment')->first();
    expect($apt->amenities)->toContain('WiFi');
    expect($apt->price_label)->toBe('NGN 60,000');
    expect($apt->image)->toStartWith('apartments/');
    Storage::disk('public')->assertExists($apt->image);
});

test('admin can update an apartment status', function () {
    $admin = User::where('is_admin', true)->first();

    $apt = Apartment::create([
        'name'        => 'Update Apt',
        'slug'        => 'update-apt',
        'type'        => 'Studio',
        'price'       => 55000,
        'price_label' => 'NGN 55,000',
        'status'      => 'Available',
        'image'       => 'https://example.com/img.jpg',
        'bedrooms'    => 1,
        'bathrooms'   => 1,
        'occupancy'   => 2,
        'description' => 'Description.',
        'amenities'   => ['WiFi'],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.apartments.update', $apt), [
        'name'        => 'Update Apt',
        'type'        => 'Studio',
        'price'       => 55000,
        'status'      => 'Maintenance',
        'image'       => 'https://example.com/img.jpg',
        'bedrooms'    => 1,
        'bathrooms'   => 1,
        'occupancy'   => 2,
        'description' => 'Description.',
        'amenities'   => 'WiFi',
        'gallery'     => '',
        'is_active'   => 1,
        'sort'        => 0,
    ]);

    $response->assertRedirect(route('admin.apartments.index'));
    $apt->refresh();
    expect($apt->status)->toBe('Maintenance');
});

test('admin can delete an apartment', function () {
    $admin = User::where('is_admin', true)->first();

    $apt = Apartment::create([
        'name'        => 'Delete Apt',
        'slug'        => 'delete-apt',
        'type'        => 'Studio',
        'price'       => 40000,
        'price_label' => 'NGN 40,000',
        'status'      => 'Available',
        'image'       => 'https://example.com/img.jpg',
        'bedrooms'    => 1,
        'bathrooms'   => 1,
        'occupancy'   => 2,
        'description' => 'Description.',
        'amenities'   => ['WiFi'],
        'is_active'   => true,
        'sort'        => 0,
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.apartments.destroy', $apt))
         ->assertRedirect(route('admin.apartments.index'));

    $this->assertDatabaseMissing('apartments', ['name' => 'Delete Apt']);
});

// ─── Payment Methods CRUD ─────────────────────────────────────────────────────

test('admin can create a payment method via POST', function () {
    $admin = User::where('is_admin', true)->first();

    $response = $this->actingAs($admin)->post(route('admin.payment-methods.store'), [
        'name'                   => 'Bank Transfer',
        'type'                   => 'bank_transfer',
        'provider'               => 'GTBank',
        'account_name'           => 'Hotel Benizia Ltd',
        'account_number'         => '0123456789',
        'bank_name'              => 'GTBank',
        'instructions'           => 'Transfer to the account above.',
        'active'                 => 1,
        'accepts_commitment_fee' => 1,
        'sort'                   => 0,
    ]);

    $response->assertRedirect(route('admin.payment-methods.index'));
    $this->assertDatabaseHas('payment_methods', ['name' => 'Bank Transfer']);
});

test('admin can update a payment method', function () {
    $admin = User::where('is_admin', true)->first();

    $pm = PaymentMethod::create([
        'name'                   => 'Old Method',
        'type'                   => 'card',
        'provider'               => 'Paystack',
        'instructions'           => 'Pay online.',
        'active'                 => true,
        'accepts_commitment_fee' => false,
        'sort'                   => 0,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.payment-methods.update', $pm), [
        'name'                   => 'Old Method',
        'type'                   => 'card',
        'provider'               => 'Flutterwave',
        'instructions'           => 'Pay online.',
        'active'                 => 1,
        'accepts_commitment_fee' => 0,
        'sort'                   => 0,
    ]);

    $response->assertRedirect(route('admin.payment-methods.index'));
    $pm->refresh();
    expect($pm->provider)->toBe('Flutterwave');
});

test('admin can delete a payment method', function () {
    $admin = User::where('is_admin', true)->first();

    $pm = PaymentMethod::create([
        'name'                   => 'Delete Method',
        'type'                   => 'card',
        'provider'               => 'Paystack',
        'instructions'           => 'Pay online.',
        'active'                 => true,
        'accepts_commitment_fee' => false,
        'sort'                   => 0,
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.payment-methods.destroy', $pm))
         ->assertRedirect(route('admin.payment-methods.index'));

    $this->assertDatabaseMissing('payment_methods', ['name' => 'Delete Method']);
});
