<?php

namespace App\Models\Concerns;

use App\Models\IcalFeed;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

/**
 * Gives a bookable a stable, unguessable token for its public .ics export
 * feed, plus the relation to imported (Booking.com / OTA) feed URLs.
 */
trait HasIcalToken
{
    public static function bootHasIcalToken(): void
    {
        static::creating(function ($model) {
            if (empty($model->ical_token)) {
                $model->ical_token = Str::random(40);
            }
        });
    }

    /**
     * Imported calendar feeds (e.g. Booking.com) pulled into this bookable.
     */
    public function icalFeeds(): MorphMany
    {
        return $this->morphMany(IcalFeed::class, 'bookable');
    }

    /**
     * Public URL of this bookable's exported availability calendar.
     */
    public function icalUrl(): string
    {
        return route('calendar.ical', ['token' => $this->ical_token]);
    }
}
