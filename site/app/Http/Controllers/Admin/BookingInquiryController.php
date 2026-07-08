<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BookingInquiry;

class BookingInquiryController extends Controller
{
    public function index()
    {
        $inquiries = BookingInquiry::latest()->paginate(20);
        return view('admin.booking-inquiries.index', compact('inquiries'));
    }
    public function show(BookingInquiry $bookingInquiry)
    {
        if ($bookingInquiry->status === 'new') {
            $bookingInquiry->update(['status' => 'handled']);
        }
        return view('admin.booking-inquiries.show', ['inquiry' => $bookingInquiry]);
    }
    public function destroy(BookingInquiry $bookingInquiry)
    {
        $bookingInquiry->delete();
        return redirect()->route('admin.booking-inquiries.index')->with('status', 'Inquiry deleted.');
    }
}
