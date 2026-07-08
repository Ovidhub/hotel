<?php
// tests/Feature/BlacktowerThemeTest.php
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    config()->set('hotel.theme', 'blacktower');
});

it('renders the black tower home with a seeded room and hero copy', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('Experience Comfort'); // hero heading from page.tsx
    $response->assertSee(\App\Models\Room::first()->name);
});

it('renders black tower rooms, about and contact pages with theme-unique markers', function () {
    // Belt-and-suspenders: the blacktower templates must actually exist,
    // otherwise theme_view() silently falls back to the flat Benizia views.
    $this->assertTrue(view()->exists('themes.blacktower.rooms.index'));
    $this->assertTrue(view()->exists('themes.blacktower.about'));
    $this->assertTrue(view()->exists('themes.blacktower.contact'));

    $this->get(route('rooms.index'))
        ->assertOk()
        // "Discover Our Rooms" is the blacktower rooms hero <h1>; Benizia's
        // resources/views/rooms/index.blade.php never contains this string.
        ->assertSee('Discover Our Rooms')
        ->assertSee(Room::first()->name);

    $this->get(route('about'))
        ->assertOk()
        // "Discover True Comfort" is the blacktower about hero <h1>; Benizia's
        // resources/views/about.blade.php uses "About Hotel Benizia" instead.
        ->assertSee('Discover True Comfort');

    $this->get(route('contact'))
        ->assertOk()
        // "Get In Touch" is the blacktower contact hero <h1>; Benizia's
        // resources/views/contact.blade.php only has the lowercase-"in"
        // eyebrow "Get in Touch" and page title "Contact Us".
        ->assertSee('Get In Touch');
});

it('renders the black tower room show page with theme-unique markers', function () {
    $this->assertTrue(view()->exists('themes.blacktower.rooms.show'));

    $room = Room::first();

    $this->get(route('rooms.show', $room))
        ->assertOk()
        // "About this room" is the blacktower room-detail section heading;
        // Benizia's resources/views/rooms/show.blade.php has no such heading
        // (it renders the description under no heading at all).
        ->assertSee('About this room')
        ->assertSee($room->name);
});
