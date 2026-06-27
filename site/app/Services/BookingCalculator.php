<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * Stateless price/commitment calculator for booking quotes.
 * Plain injectable class — no facade dependencies.
 */
class BookingCalculator
{
    /**
     * Compute a booking quote.
     *
     * @param  int     $price             Price per night in kobo/smallest unit (or naira integer).
     * @param  Carbon  $checkIn           Check-in date.
     * @param  Carbon  $checkOut          Check-out date.
     * @param  int     $commitmentPercent Percentage of total charged as commitment fee (e.g. 40).
     * @return array{nights:int, total:int, commitment_fee:int, balance_due:int}
     */
    public function quote(int $price, Carbon $checkIn, Carbon $checkOut, int $commitmentPercent): array
    {
        // Clamp nights to minimum 1 (handles same-day and reversed dates)
        $nights = max(1, (int) $checkIn->diffInDays($checkOut));

        $total          = $price * $nights;
        $commitmentFee  = (int) round($total * $commitmentPercent / 100);
        $balanceDue     = $total - $commitmentFee;

        return [
            'nights'         => $nights,
            'total'          => $total,
            'commitment_fee' => $commitmentFee,
            'balance_due'    => $balanceDue,
        ];
    }
}
