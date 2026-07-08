<?php
namespace App\Http\Controllers;
use App\Http\Requests\BookingInquiryRequest;
use App\Mail\BookingInquiryReceived;
use App\Models\BookingInquiry;
use Illuminate\Support\Facades\Mail;

class BookingInquiryController extends Controller
{
    public function store(BookingInquiryRequest $request)
    {
        $inquiry = BookingInquiry::create($request->validated());
        Mail::to(config('hotel.email'))->send(new BookingInquiryReceived($inquiry));
        return back()->with('status', 'Thank you — your booking request has been received. We will contact you shortly.');
    }
}
