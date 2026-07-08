<?php

namespace App\Http\Controllers;

use App\Mail\BookingReceived;
use App\Models\Booking;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page for a booking.
     * Displays the booking summary and active payment methods that accept the commitment fee.
     */
    public function show(Booking $booking)
    {
        $paymentMethods = PaymentMethod::where('active', true)
            ->where('accepts_commitment_fee', true)
            ->orderBy('sort')
            ->get();

        $booking->load('bookable');
        $balanceNote = config('hotel.booking.balance_note');

        return view(theme_view('checkout.index'), compact('booking', 'paymentMethods', 'balanceNote'));
    }

    /**
     * Handle payment method selection and optional proof upload.
     */
    public function confirm(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method_id' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $method = PaymentMethod::find($value);
                    if (! $method) {
                        $fail('The selected payment method does not exist.');
                        return;
                    }
                    if (! $method->active) {
                        $fail('The selected payment method is not currently available.');
                    }
                },
            ],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'payment_method_id.required' => 'Please select a payment method.',
        ]);

        $data = [
            'payment_method_id' => $request->integer('payment_method_id'),
        ];

        if ($request->hasFile('proof') && $request->file('proof')->isValid()) {
            $data['proof_path'] = $request->file('proof')->store('proofs', 'public');
        }

        if ($request->filled('notes')) {
            $data['notes'] = $request->input('notes');
        }

        $booking->update($data);

        // Acknowledge receipt to the guest (pending verification). Never block on mail failure.
        try {
            Mail::to($booking->guest_email)->send(
                new BookingReceived($booking->fresh(), isset($data['proof_path']))
            );
        } catch (\Throwable $e) {
            Log::error('Booking-received email failed for ' . $booking->ref . ': ' . $e->getMessage());
        }

        return redirect()->route('booking.success', ['booking' => $booking->ref]);
    }
}
