<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Apartment;
use App\Services\BookingCalculator;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(protected BookingCalculator $calculator) {}

    /**
     * Show the booking form for a room or apartment.
     */
    public function create(string $type, string $slug)
    {
        $bookable = match ($type) {
            'room'      => Room::where('slug', $slug)->firstOrFail(),
            'apartment' => Apartment::where('slug', $slug)->firstOrFail(),
            default     => abort(404),
        };

        $booking = config('hotel.booking');

        return view('booking.create', [
            'bookable'          => $bookable,
            'type'              => $type,
            'price'             => $bookable->price,
            'priceFormatted'    => $bookable->price_formatted,
            'commitmentPercent' => $booking['commitment_percent'],
            'cancellationHours' => $booking['cancellation_hours'],
            'balanceNote'       => $booking['balance_note'],
        ]);
    }

    /**
     * STUB — Task 11 will replace this with the full checkout / booking persistence.
     * For now: validate the minimum required fields and redirect back with input
     * so the form does not produce a blank 405 error during development.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'         => ['required', 'in:room,apartment'],
            'slug'         => ['required', 'string'],
            'check_in'     => ['required', 'date', 'after_or_equal:today'],
            'check_out'    => ['required', 'date', 'after:check_in'],
            'guests'       => ['required', 'integer', 'min:1'],
            'guest_name'   => ['required', 'string', 'max:255'],
            'guest_email'  => ['required', 'email', 'max:255'],
            'guest_phone'  => ['required', 'string', 'max:50'],
        ]);

        // Task 11: create Booking record, charge commitment fee, redirect to checkout.
        return redirect()
            ->route('booking.create', ['type' => $request->type, 'slug' => $request->slug])
            ->withInput()
            ->with('info', 'Booking submission received — checkout coming soon.');
    }
}
