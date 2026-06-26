<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingCalculator;
use Carbon\Carbon;
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
     * Validate booking details, persist the booking with 'Pending Payment' status,
     * and redirect to the checkout page where the guest selects a payment method.
     */
    public function store(StoreBookingRequest $request)
    {
        $type = $request->input('type');
        $slug = $request->input('slug');

        // Resolve the bookable
        $bookable = match ($type) {
            'room'      => Room::where('slug', $slug)->firstOrFail(),
            'apartment' => Apartment::where('slug', $slug)->firstOrFail(),
        };

        // Compute quote
        $checkIn  = Carbon::parse($request->input('check_in'));
        $checkOut = Carbon::parse($request->input('check_out'));
        $percent  = config('hotel.booking.commitment_percent', 40);

        $quote = $this->calculator->quote($bookable->price, $checkIn, $checkOut, $percent);

        // Generate unique ref: HB-##### (5 digits, zero-padded)
        do {
            $ref = 'HB-' . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Booking::where('ref', $ref)->exists());

        // Persist booking
        $booking = Booking::create([
            'ref'                => $ref,
            'bookable_type'      => get_class($bookable),
            'bookable_id'        => $bookable->id,
            'guest_name'         => $request->input('guest_name'),
            'guest_email'        => $request->input('guest_email'),
            'guest_phone'        => $request->input('guest_phone'),
            'check_in'           => $checkIn->toDateString(),
            'check_out'          => $checkOut->toDateString(),
            'nights'             => $quote['nights'],
            'guests'             => $request->integer('guests'),
            'total'              => $quote['total'],
            'commitment_percent' => $percent,
            'commitment_fee'     => $quote['commitment_fee'],
            'balance_due'        => $quote['balance_due'],
            'status'             => 'Pending Payment',
            'notes'              => $request->input('notes'),
        ]);

        return redirect()->route('checkout.show', ['booking' => $booking->ref]);
    }

    /**
     * Show the booking confirmation / success page.
     */
    public function success(Booking $booking)
    {
        $booking->load(['bookable', 'paymentMethod']);

        $balanceNote = config('hotel.booking.balance_note');

        return view('checkout.success', compact('booking', 'balanceNote'));
    }
}
