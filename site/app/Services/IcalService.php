<?php

namespace App\Services;

use App\Models\IcalFeed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

/**
 * Exports a bookable's availability as an .ics feed (for Booking.com et al.
 * to import) and imports external .ics feeds into availability blocks.
 *
 * iCal dates are half-open: DTEND is the day AFTER the last occupied night.
 */
class IcalService
{
    /**
     * Build the .ics calendar text for a bookable's occupied dates
     * (active bookings + all blocks).
     */
    public function generate(Model $bookable): string
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'hotelbenizia.ng';
        $stamp = now()->utc()->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Hotel Benizia//Availability//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
        ];

        $events = [];

        foreach ($bookable->bookings()->whereIn('status', AvailabilityService::OCCUPYING_STATUSES)->get() as $booking) {
            $events[] = [
                'uid'     => "booking-{$booking->id}@{$host}",
                'start'   => Carbon::parse($booking->check_in),
                'end'     => Carbon::parse($booking->check_out), // already exclusive
                'summary' => 'Booked',
            ];
        }

        foreach ($bookable->availabilityBlocks()->get() as $block) {
            $events[] = [
                'uid'     => "block-{$block->id}@{$host}",
                'start'   => Carbon::parse($block->start_date),
                'end'     => Carbon::parse($block->end_date)->addDay(), // inclusive -> exclusive
                'summary' => $block->reason ?: 'Unavailable',
            ];
        }

        foreach ($events as $e) {
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $e['uid'];
            $lines[] = 'DTSTAMP:' . $stamp;
            $lines[] = 'DTSTART;VALUE=DATE:' . $e['start']->format('Ymd');
            $lines[] = 'DTEND;VALUE=DATE:' . $e['end']->format('Ymd');
            $lines[] = 'SUMMARY:' . $this->escapeText($e['summary']);
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines) . "\r\n";
    }

    /**
     * Parse VEVENT date ranges from .ics text.
     *
     * @return array<int, array{start: Carbon, end: Carbon}> end is exclusive
     */
    public function parseEvents(string $ics): array
    {
        // Unfold folded lines (RFC 5545: continuation lines start with space/tab).
        $unfolded = preg_replace("/\r\n[ \t]/", '', $ics);
        $unfolded = preg_replace("/\n[ \t]/", '', $unfolded);
        $lines = preg_split("/\r\n|\n|\r/", (string) $unfolded);

        $events = [];
        $inEvent = false;
        $start = null;
        $end = null;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === 'BEGIN:VEVENT') {
                $inEvent = true;
                $start = $end = null;
                continue;
            }

            if ($trimmed === 'END:VEVENT') {
                if ($start) {
                    $events[] = [
                        'start' => $start,
                        'end'   => $end ?: $start->copy()->addDay(),
                    ];
                }
                $inEvent = false;
                continue;
            }

            if (! $inEvent) {
                continue;
            }

            if (str_starts_with($trimmed, 'DTSTART')) {
                $start = $this->parseIcalDate($trimmed);
            } elseif (str_starts_with($trimmed, 'DTEND')) {
                $end = $this->parseIcalDate($trimmed);
            }
        }

        return $events;
    }

    /**
     * Fetch a feed and replace its imported blocks with the current contents.
     *
     * @return array{ok: bool, imported: int, error: ?string}
     */
    public function syncFeed(IcalFeed $feed): array
    {
        try {
            $response = Http::timeout(20)->get($feed->url);

            if (! $response->successful()) {
                return $this->recordResult($feed, false, 0, 'HTTP ' . $response->status());
            }

            $events = $this->parseEvents($response->body());

            // Replace previously-imported blocks for this feed.
            $feed->bookable->availabilityBlocks()->where('ical_feed_id', $feed->id)->delete();

            $imported = 0;
            foreach ($events as $event) {
                $lastNight = $event['end']->copy()->subDay(); // exclusive -> inclusive
                if ($lastNight->lessThan($event['start'])) {
                    continue;
                }

                $feed->bookable->availabilityBlocks()->create([
                    'start_date'   => $event['start']->toDateString(),
                    'end_date'     => $lastNight->toDateString(),
                    'reason'       => $feed->label ?: 'Imported',
                    'source'       => $feed->label ?: 'ical',
                    'ical_feed_id' => $feed->id,
                ]);
                $imported++;
            }

            return $this->recordResult($feed, true, $imported, null);
        } catch (\Throwable $e) {
            return $this->recordResult($feed, false, 0, $e->getMessage());
        }
    }

    protected function recordResult(IcalFeed $feed, bool $ok, int $imported, ?string $error): array
    {
        $feed->update([
            'last_synced_at' => now(),
            'last_error'     => $error,
        ]);

        return ['ok' => $ok, 'imported' => $imported, 'error' => $error];
    }

    protected function parseIcalDate(string $line): ?Carbon
    {
        // Take everything after the first colon, then the leading 8 digits (YYYYMMDD).
        $value = substr($line, strpos($line, ':') + 1);
        if (preg_match('/(\d{8})/', $value, $m)) {
            return Carbon::createFromFormat('Ymd', $m[1])->startOfDay();
        }

        return null;
    }

    protected function escapeText(string $text): string
    {
        return str_replace([',', ';'], ['\,', '\;'], $text);
    }
}
