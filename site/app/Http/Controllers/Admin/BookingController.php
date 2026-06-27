<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    private const ALLOWED_STATUSES = [
        'Pending Payment',
        'Confirmed',
        'Checked In',
        'Cancelled',
    ];

    public function index(Request $request)
    {
        $status = in_array($request->query('status'), self::ALLOWED_STATUSES, true)
            ? $request->query('status')
            : null;

        $bookings = Booking::with(['bookable', 'paymentMethod'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('admin.bookings.index', [
            'bookings'        => $bookings,
            'statuses'        => self::ALLOWED_STATUSES,
            'currentStatus'   => $status,
        ]);
    }

    public function show(Booking $booking)
    {
        $booking->load(['bookable', 'paymentMethod']);

        $proofUrl = $booking->proof_path
            ? Storage::url($booking->proof_path)
            : null;

        return view('admin.bookings.show', compact('booking', 'proofUrl'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->route('admin.bookings.show', $booking)
                         ->with('status', 'Booking status updated successfully.');
    }
}
