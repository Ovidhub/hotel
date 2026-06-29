<?php

namespace App\Console\Commands;

use App\Models\IcalFeed;
use App\Services\IcalService;
use Illuminate\Console\Command;

class SyncCalendars extends Command
{
    protected $signature = 'calendar:sync';

    protected $description = 'Import all configured Booking.com / OTA iCal feeds into availability blocks';

    public function handle(IcalService $ical): int
    {
        $feeds = IcalFeed::with('bookable')->get();

        if ($feeds->isEmpty()) {
            $this->info('No calendar feeds configured.');

            return self::SUCCESS;
        }

        foreach ($feeds as $feed) {
            $result = $ical->syncFeed($feed);
            $name = ($feed->bookable?->name ?? 'unknown') . ' / ' . ($feed->label ?: 'feed');

            $result['ok']
                ? $this->info("✓ {$name}: imported {$result['imported']}")
                : $this->error("✗ {$name}: {$result['error']}");
        }

        return self::SUCCESS;
    }
}
