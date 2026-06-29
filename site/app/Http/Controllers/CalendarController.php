<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Room;
use App\Services\IcalService;

class CalendarController extends Controller
{
    public function __construct(protected IcalService $ical) {}

    /**
     * Public .ics availability feed for a room or apartment, identified by
     * its unguessable token. Consumed by Booking.com / Airbnb / etc.
     */
    public function show(string $token)
    {
        $bookable = Room::where('ical_token', $token)->first()
            ?? Apartment::where('ical_token', $token)->first()
            ?? abort(404);

        return response($this->ical->generate($bookable), 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="hotel-benizia-' . $bookable->slug . '.ics"',
        ]);
    }
}
