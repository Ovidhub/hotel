<?php
namespace App\Http\Controllers;
use App\Http\Requests\BookingInquiryRequest;
use App\Mail\BookingInquiryReceived;
use App\Models\BookingInquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingInquiryController extends Controller
{
    public function store(BookingInquiryRequest $request)
    {
        $inquiry = BookingInquiry::create($request->validated());

        // Notify the hotel — wrapped in try/catch so mail failure never blocks submission
        try {
            Mail::to(config('hotel.email'))->send(new BookingInquiryReceived($inquiry));
        } catch (\Throwable $e) {
            Log::error('Booking inquiry mail failed: ' . $e->getMessage());
        }

        return back()->with('status', 'Thank you — your booking request has been received. We will contact you shortly.');
    }
}
