<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

/**
 * Determines whether a room/apartment is bookable for a date range,
 * accounting for unit inventory, active bookings, and admin/OTA blocks.
 *
 * Nights are half-open: a stay [check_in, check_out) occupies the nights
 * from check_in up to (but not including) check_out, so the check-out day
 * is free for the next guest.
 */
class AvailabilityService
{
    /** Booking statuses that occupy a unit (i.e. not cancelled). */
    public const OCCUPYING_STATUSES = ['Pending Payment', 'Confirmed', 'Checked In'];

    /**
     * Is the bookable available for every night in [checkIn, checkOut)?
     */
    public function isRangeAvailable(Model $bookable, Carbon $checkIn, Carbon $checkOut, ?int $ignoreBookingId = null): bool
    {
        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            return false;
        }

        $units = max(1, (int) ($bookable->units ?? 1));

        foreach ($this->nights($checkIn, $checkOut) as $night) {
            if ($this->isNightBlocked($bookable, $night)) {
                return false;
            }

            if ($this->bookedUnits($bookable, $night, $ignoreBookingId) >= $units) {
                return false;
            }
        }

        return true;
    }

    /**
     * Dates (Y-m-d) within [from, to) that cannot be booked — blocked or
     * fully booked. Useful for disabling dates in a calendar UI.
     *
     * @return array<int, string>
     */
    public function unavailableDates(Model $bookable, Carbon $from, Carbon $to): array
    {
        $units = max(1, (int) ($bookable->units ?? 1));
        $dates = [];

        foreach ($this->nights($from, $to) as $night) {
            if ($this->isNightBlocked($bookable, $night) || $this->bookedUnits($bookable, $night) >= $units) {
                $dates[] = $night->toDateString();
            }
        }

        return $dates;
    }

    /**
     * @return iterable<Carbon>
     */
    protected function nights(Carbon $checkIn, Carbon $checkOut): iterable
    {
        // Period end is exclusive of the check-out day.
        return CarbonPeriod::create($checkIn->copy()->startOfDay(), $checkOut->copy()->startOfDay()->subDay());
    }

    protected function isNightBlocked(Model $bookable, Carbon $night): bool
    {
        return $bookable->availabilityBlocks()
            ->whereDate('start_date', '<=', $night)
            ->whereDate('end_date', '>=', $night)
            ->exists();
    }

    protected function bookedUnits(Model $bookable, Carbon $night, ?int $ignoreBookingId = null): int
    {
        return $bookable->bookings()
            ->whereIn('status', self::OCCUPYING_STATUSES)
            ->when($ignoreBookingId, fn ($q) => $q->where('id', '!=', $ignoreBookingId))
            ->whereDate('check_in', '<=', $night)
            ->whereDate('check_out', '>', $night)
            ->count();
    }
}
