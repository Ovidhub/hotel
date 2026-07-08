<?php
namespace App\Mail;
use App\Models\BookingInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInquiryReceived extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public BookingInquiry $inquiry) {}
    public function build()
    {
        return $this->subject('New booking inquiry — '.$this->inquiry->room)
                    ->view('emails.booking-inquiry-received');
    }
}
