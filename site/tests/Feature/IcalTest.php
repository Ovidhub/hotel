<?php

use App\Models\Room;
use App\Models\User;
use App\Services\IcalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function sampleIcs(string $start = '20300101', string $end = '20300105'): string
{
    return implode("\r\n", [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//Booking.com//EN',
        'BEGIN:VEVENT',
        'UID:abc-123@booking.com',
        'DTSTART;VALUE=DATE:' . $start,
        'DTEND;VALUE=DATE:' . $end,
        'SUMMARY:CLOSED - Not available',
        'END:VEVENT',
        'END:VCALENDAR',
    ]) . "\r\n";
}

// ─── Export ───────────────────────────────────────────────────────────────────

test('rooms get an ical token automatically', function () {
    expect(Room::first()->ical_token)->not->toBeNull()->toHaveLength(40);
});

test('the public ical feed returns calendar text with booked dates', function () {
    $room = Room::first();
    $room->update(['units' => 1]);
    $room->availabilityBlocks()->create([
        'start_date' => '2030-07-01',
        'end_date'   => '2030-07-03',
        'source'     => 'manual',
    ]);

    $response = $this->get(route('calendar.ical', ['token' => $room->ical_token]));

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('text/calendar');
    expect($response->getContent())
        ->toContain('BEGIN:VCALENDAR')
        ->toContain('BEGIN:VEVENT')
        ->toContain('DTSTART;VALUE=DATE:20300701')
        // inclusive end 07-03 -> exclusive DTEND 07-04
        ->toContain('DTEND;VALUE=DATE:20300704');
});

test('an unknown ical token returns 404', function () {
    $this->get(route('calendar.ical', ['token' => 'doesnotexist']))->assertNotFound();
});

// ─── Parse ──────────────────────────────────────────────────────────────────

test('parseEvents reads DTSTART/DTEND date ranges', function () {
    $events = app(IcalService::class)->parseEvents(sampleIcs('20300101', '20300105'));

    expect($events)->toHaveCount(1);
    expect($events[0]['start']->toDateString())->toBe('2030-01-01');
    expect($events[0]['end']->toDateString())->toBe('2030-01-05'); // exclusive
});

// ─── Import / sync ────────────────────────────────────────────────────────────

test('syncing a feed imports blocks and makes those dates unavailable', function () {
    Http::fake(['*' => Http::response(sampleIcs('20300201', '20300205'), 200)]);

    $room = Room::first();
    $feed = $room->icalFeeds()->create(['label' => 'Booking.com', 'url' => 'https://ical.booking.com/x.ics']);

    $result = app(IcalService::class)->syncFeed($feed);

    expect($result['ok'])->toBeTrue();
    expect($result['imported'])->toBe(1);
    $this->assertDatabaseHas('availability_blocks', [
        'bookable_type' => Room::class,
        'bookable_id'   => $room->id,
        'ical_feed_id'  => $feed->id,
        'start_date'    => '2030-02-01 00:00:00',
        'end_date'      => '2030-02-04 00:00:00', // exclusive 02-05 -> inclusive 02-04
    ]);
    expect($feed->fresh()->last_synced_at)->not->toBeNull();
});

test('re-syncing replaces previously imported blocks', function () {
    // Two successive fetches return different calendars.
    Http::fake(['*' => Http::sequence()
        ->push(sampleIcs('20300301', '20300305'))
        ->push(sampleIcs('20300401', '20300403'))]);

    $room = Room::first();
    $feed = $room->icalFeeds()->create(['label' => 'Booking.com', 'url' => 'https://ical.booking.com/x.ics']);

    app(IcalService::class)->syncFeed($feed);
    expect($room->availabilityBlocks()->where('ical_feed_id', $feed->id)->count())->toBe(1);

    // Second sync: old import should be replaced, not stacked.
    app(IcalService::class)->syncFeed($feed);

    $blocks = $room->availabilityBlocks()->where('ical_feed_id', $feed->id)->get();
    expect($blocks)->toHaveCount(1);
    expect($blocks->first()->start_date->toDateString())->toBe('2030-04-01');
});

test('a failed feed fetch records an error and imports nothing', function () {
    Http::fake(['*' => Http::response('', 500)]);

    $room = Room::first();
    $feed = $room->icalFeeds()->create(['label' => 'Booking.com', 'url' => 'https://ical.booking.com/bad.ics']);

    $result = app(IcalService::class)->syncFeed($feed);

    expect($result['ok'])->toBeFalse();
    expect($feed->fresh()->last_error)->not->toBeNull();
    expect($room->availabilityBlocks()->where('ical_feed_id', $feed->id)->count())->toBe(0);
});

// ─── Admin feed management ─────────────────────────────────────────────────────

test('admin can add an import feed and it syncs immediately', function () {
    Http::fake(['*' => Http::response(sampleIcs('20300501', '20300504'), 200)]);

    $admin = User::where('is_admin', true)->first();
    $room = Room::first();

    $this->actingAs($admin)->post(route('admin.availability.feeds.store', ['type' => 'room', 'id' => $room->id]), [
        'label' => 'Booking.com',
        'url'   => 'https://ical.booking.com/v1/export?token=abc',
    ])->assertRedirect();

    $this->assertDatabaseHas('ical_feeds', ['bookable_id' => $room->id, 'label' => 'Booking.com']);
    expect($room->availabilityBlocks()->where('source', 'Booking.com')->count())->toBe(1);
});

test('admin removing a feed also removes its imported blocks', function () {
    Http::fake(['*' => Http::response(sampleIcs('20300601', '20300604'), 200)]);

    $admin = User::where('is_admin', true)->first();
    $room = Room::first();
    $feed = $room->icalFeeds()->create(['label' => 'Booking.com', 'url' => 'https://ical.booking.com/x.ics']);
    app(IcalService::class)->syncFeed($feed);
    expect($room->availabilityBlocks()->count())->toBeGreaterThan(0);

    $this->actingAs($admin)->delete(route('admin.availability.feeds.destroy', $feed))->assertRedirect();

    $this->assertDatabaseMissing('ical_feeds', ['id' => $feed->id]);
    expect($room->availabilityBlocks()->where('ical_feed_id', $feed->id)->count())->toBe(0);
});

test('feed management is gated to admins', function () {
    $room = Room::first();
    $this->post(route('admin.availability.feeds.store', ['type' => 'room', 'id' => $room->id]), [])
         ->assertRedirect(route('login'));
});
