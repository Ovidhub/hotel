<?php

use App\Models\Booking;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

// ─── Middleware: non-admin gets 403 ──────────────────────────────────────────

test('non-admin gets 403 when accessing admin bookings index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
         ->get(route('admin.bookings.index'))
         ->assertForbidden();
});

// ─── Bookings ────────────────────────────────────────────────────────────────

test('admin can view bookings index and see booking ref', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();
    $pm    = PaymentMethod::first();

    $booking = Booking::create([
        'ref'                => 'BK-TEST-001',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Alice Wonder',
        'guest_email'        => 'alice@example.com',
        'guest_phone'        => '08012345678',
        'check_in'           => '2026-07-10',
        'check_out'          => '2026-07-13',
        'nights'             => 3,
        'guests'             => 2,
        'total'              => 120000,
        'commitment_percent' => 30,
        'commitment_fee'     => 36000,
        'balance_due'        => 84000,
        'status'             => 'Pending Payment',
        'payment_method_id'  => $pm->id,
    ]);

    $this->actingAs($admin)
         ->get(route('admin.bookings.index'))
         ->assertOk()
         ->assertSee('BK-TEST-001');
});

test('admin can view a single booking show page', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();

    $booking = Booking::create([
        'ref'                => 'BK-TEST-002',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Bob Marley',
        'guest_email'        => 'bob@example.com',
        'guest_phone'        => '08099887766',
        'check_in'           => '2026-08-01',
        'check_out'          => '2026-08-05',
        'nights'             => 4,
        'guests'             => 1,
        'total'              => 80000,
        'commitment_percent' => 30,
        'commitment_fee'     => 24000,
        'balance_due'        => 56000,
        'status'             => 'Pending Payment',
    ]);

    $this->actingAs($admin)
         ->get(route('admin.bookings.show', $booking))
         ->assertOk()
         ->assertSee('BK-TEST-002')
         ->assertSee('Bob Marley');
});

test('admin can update a booking status to Confirmed', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();

    $booking = Booking::create([
        'ref'                => 'BK-TEST-003',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Carol Jones',
        'guest_email'        => 'carol@example.com',
        'guest_phone'        => '07011223344',
        'check_in'           => '2026-09-01',
        'check_out'          => '2026-09-04',
        'nights'             => 3,
        'guests'             => 2,
        'total'              => 90000,
        'commitment_percent' => 30,
        'commitment_fee'     => 27000,
        'balance_due'        => 63000,
        'status'             => 'Pending Payment',
    ]);

    $this->actingAs($admin)
         ->put(route('admin.bookings.update', $booking), ['status' => 'Confirmed'])
         ->assertRedirect(route('admin.bookings.show', $booking));

    $booking->refresh();
    expect($booking->status)->toBe('Confirmed');
});

test('admin cannot update booking to an invalid status', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();

    $booking = Booking::create([
        'ref'                => 'BK-TEST-004',
        'bookable_type'      => Room::class,
        'bookable_id'        => $room->id,
        'guest_name'         => 'Dave Smith',
        'guest_email'        => 'dave@example.com',
        'guest_phone'        => '07099887766',
        'check_in'           => '2026-10-01',
        'check_out'          => '2026-10-03',
        'nights'             => 2,
        'guests'             => 1,
        'total'              => 50000,
        'commitment_percent' => 30,
        'commitment_fee'     => 15000,
        'balance_due'        => 35000,
        'status'             => 'Pending Payment',
    ]);

    $this->actingAs($admin)
         ->put(route('admin.bookings.update', $booking), ['status' => 'Hacked'])
         ->assertSessionHasErrors('status');
});

test('admin can filter bookings by status', function () {
    $admin = User::where('is_admin', true)->first();
    $room  = Room::first();

    Booking::create([
        'ref' => 'BK-PEND-001', 'bookable_type' => Room::class, 'bookable_id' => $room->id,
        'guest_name' => 'Pending Guest', 'guest_email' => 'p@x.com', 'guest_phone' => '0800000001',
        'check_in' => '2026-07-01', 'check_out' => '2026-07-02', 'nights' => 1, 'guests' => 1,
        'total' => 20000, 'commitment_percent' => 30, 'commitment_fee' => 6000, 'balance_due' => 14000,
        'status' => 'Pending Payment',
    ]);

    Booking::create([
        'ref' => 'BK-CONF-001', 'bookable_type' => Room::class, 'bookable_id' => $room->id,
        'guest_name' => 'Confirmed Guest', 'guest_email' => 'c@x.com', 'guest_phone' => '0800000002',
        'check_in' => '2026-07-05', 'check_out' => '2026-07-07', 'nights' => 2, 'guests' => 1,
        'total' => 40000, 'commitment_percent' => 30, 'commitment_fee' => 12000, 'balance_due' => 28000,
        'status' => 'Confirmed',
    ]);

    $response = $this->actingAs($admin)
         ->get(route('admin.bookings.index', ['status' => 'Confirmed']))
         ->assertOk()
         ->assertSee('Confirmed Guest');

    // Pending booking should not appear when filtering for Confirmed
    $response->assertDontSee('Pending Guest');
});

// ─── Blog ────────────────────────────────────────────────────────────────────

test('admin can view blog index', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)
         ->get(route('admin.blog.index'))
         ->assertOk();
});

test('admin can create a blog post and it appears on public blog', function () {
    $admin = User::where('is_admin', true)->first();

    $response = $this->actingAs($admin)->post(route('admin.blog.store'), [
        'title'        => 'Test Blog Post',
        'excerpt'      => 'A short excerpt for the test post.',
        'body'         => 'This is the full body of the test post content.',
        'category'     => 'News',
        'category_color' => '#1D5C52',
        'image'        => 'https://example.com/blog-img.jpg',
        'author'       => 'Admin User',
        'published_at' => now()->format('Y-m-d\TH:i'),
    ]);

    $response->assertRedirect(route('admin.blog.index'));
    $this->assertDatabaseHas('blog_posts', ['title' => 'Test Blog Post']);

    // Should appear on public blog
    $this->get(route('blog.index'))
         ->assertOk()
         ->assertSee('Test Blog Post');
});

test('admin can update a blog post', function () {
    $admin = User::where('is_admin', true)->first();

    $post = BlogPost::create([
        'slug'         => 'update-me-post',
        'title'        => 'Update Me Post',
        'excerpt'      => 'Before update.',
        'body'         => 'Body before update.',
        'category'     => 'Events',
        'image'        => 'https://example.com/img.jpg',
        'author'       => 'Admin',
        'published_at' => now()->subHour(),
    ]);

    $this->actingAs($admin)->put(route('admin.blog.update', $post), [
        'title'        => 'Updated Post Title',
        'excerpt'      => 'Updated excerpt.',
        'body'         => 'Updated body content.',
        'category'     => 'News',
        'image'        => 'https://example.com/img.jpg',
        'author'       => 'Admin',
        'published_at' => now()->format('Y-m-d\TH:i'),
    ])->assertRedirect(route('admin.blog.index'));

    $post->refresh();
    expect($post->title)->toBe('Updated Post Title');
});

test('admin can delete a blog post', function () {
    $admin = User::where('is_admin', true)->first();

    $post = BlogPost::create([
        'slug'         => 'delete-me-post',
        'title'        => 'Delete Me Post',
        'excerpt'      => 'About to be deleted.',
        'body'         => 'Body here.',
        'category'     => 'Events',
        'image'        => 'https://example.com/img.jpg',
        'author'       => 'Admin',
        'published_at' => now()->subHour(),
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.blog.destroy', $post))
         ->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseMissing('blog_posts', ['title' => 'Delete Me Post']);
});

// ─── Testimonials ────────────────────────────────────────────────────────────

test('admin can create a testimonial', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)->post(route('admin.testimonials.store'), [
        'name'   => 'Jane Doe',
        'role'   => 'Business Traveller',
        'rating' => 5,
        'text'   => 'Absolutely wonderful stay at Hotel Benizia!',
    ])->assertRedirect(route('admin.testimonials.index'));

    $this->assertDatabaseHas('testimonials', ['name' => 'Jane Doe', 'rating' => 5]);
});

test('admin can delete a testimonial', function () {
    $admin = User::where('is_admin', true)->first();

    $testimonial = Testimonial::create([
        'name'   => 'Delete Me',
        'role'   => 'Leisure',
        'rating' => 3,
        'text'   => 'Average stay.',
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.testimonials.destroy', $testimonial))
         ->assertRedirect(route('admin.testimonials.index'));

    $this->assertDatabaseMissing('testimonials', ['name' => 'Delete Me']);
});

// ─── FAQs ────────────────────────────────────────────────────────────────────

test('admin can create a faq', function () {
    $admin = User::where('is_admin', true)->first();

    $this->actingAs($admin)->post(route('admin.faqs.store'), [
        'question' => 'What is the check-in time?',
        'answer'   => 'Check-in is from 2:00 PM.',
        'sort'     => 10,
    ])->assertRedirect(route('admin.faqs.index'));

    $this->assertDatabaseHas('faqs', ['question' => 'What is the check-in time?']);
});

test('admin can delete a faq', function () {
    $admin = User::where('is_admin', true)->first();

    $faq = Faq::create([
        'question' => 'Delete FAQ?',
        'answer'   => 'Yes, delete it.',
        'sort'     => 0,
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.faqs.destroy', $faq))
         ->assertRedirect(route('admin.faqs.index'));

    $this->assertDatabaseMissing('faqs', ['question' => 'Delete FAQ?']);
});

// ─── Messages ────────────────────────────────────────────────────────────────

test('admin can view messages index', function () {
    $admin = User::where('is_admin', true)->first();

    Message::create([
        'name'    => 'Eve Sender',
        'email'   => 'eve@example.com',
        'phone'   => '08055667788',
        'subject' => 'General Enquiry',
        'message' => 'I would like to know more about your services.',
    ]);

    $this->actingAs($admin)
         ->get(route('admin.messages.index'))
         ->assertOk()
         ->assertSee('Eve Sender');
});

test('admin viewing a message show page marks it as read', function () {
    $admin = User::where('is_admin', true)->first();

    $message = Message::create([
        'name'    => 'Frank Unread',
        'email'   => 'frank@example.com',
        'phone'   => '08099001122',
        'subject' => 'Room Availability',
        'message' => 'Is the presidential suite available for August?',
        'read_at' => null,
    ]);

    expect($message->read_at)->toBeNull();

    $this->actingAs($admin)
         ->get(route('admin.messages.show', $message))
         ->assertOk()
         ->assertSee('Frank Unread');

    $message->refresh();
    expect($message->read_at)->not->toBeNull();
});

test('viewing a message twice does not overwrite read_at', function () {
    $admin = User::where('is_admin', true)->first();

    $readTime = now()->subDay();
    $message = Message::create([
        'name'    => 'Grace Read',
        'email'   => 'grace@example.com',
        'phone'   => '08011223355',
        'subject' => 'Already Read',
        'message' => 'This was already read.',
        'read_at' => $readTime,
    ]);

    $this->actingAs($admin)->get(route('admin.messages.show', $message));

    $message->refresh();
    // read_at should remain the original value (within a few seconds tolerance)
    expect($message->read_at->timestamp)->toBe($readTime->timestamp);
});

test('admin can delete a message', function () {
    $admin = User::where('is_admin', true)->first();

    $message = Message::create([
        'name'    => 'Henry Delete',
        'email'   => 'henry@example.com',
        'phone'   => '07011334455',
        'subject' => 'Delete This',
        'message' => 'Please delete this message.',
    ]);

    $this->actingAs($admin)
         ->delete(route('admin.messages.destroy', $message))
         ->assertRedirect(route('admin.messages.index'));

    $this->assertDatabaseMissing('messages', ['name' => 'Henry Delete']);
});
