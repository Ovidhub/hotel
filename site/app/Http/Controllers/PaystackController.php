<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaystackController extends Controller
{
    /**
     * Initiate a Paystack payment for a booking's commitment fee.
     *
     * NO-KEYS PATH (default for this project):
     *   If PAYSTACK_SECRET_KEY is not configured, redirects back to the checkout
     *   page with a friendly flash message. The booking is left untouched.
     *
     * LIVE INTEGRATION PATH (requires .env keys):
     *   Builds the transaction payload and calls Paystack's initialize endpoint.
     *   On success, redirects the guest to Paystack's hosted payment page.
     */
    public function init(Booking $booking)
    {
        // LIVE INTEGRATION: requires PAYSTACK_SECRET_KEY in .env
        $secret = config('services.paystack.secret');

        if (empty($secret)) {
            // No-keys path: short-circuit gracefully with a clear user-facing message.
            return redirect()
                ->route('checkout.show', ['booking' => $booking->ref])
                ->with('status', 'Online card payment (Paystack) is not configured yet. Please use bank transfer, or contact us to complete payment.');
        }

        // ── LIVE PATH ──────────────────────────────────────────────────────────
        // This code runs only when PAYSTACK_SECRET_KEY is set in .env.
        // LIVE INTEGRATION: remove the stub comment and test end-to-end with real keys.
        try {
            $baseUrl  = config('services.paystack.base_url');
            $currency = config('services.paystack.currency');

            // Amount must be in kobo (Paystack's lowest denomination): multiply NGN by 100.
            $payload = [
                'email'        => $booking->guest_email,
                'amount'       => (int) ($booking->commitment_fee * 100), // kobo
                'reference'    => $booking->ref,
                'callback_url' => route('paystack.callback'),
                'currency'     => $currency,
            ];

            // LIVE INTEGRATION: this HTTP call hits the real Paystack API.
            $response = Http::withToken($secret)
                ->post("{$baseUrl}/transaction/initialize", $payload);

            if ($response->successful() && $response->json('status') === true) {
                $authUrl = $response->json('data.authorization_url');
                return redirect()->away($authUrl);
            }

            // Paystack returned a non-success response.
            return redirect()
                ->route('checkout.show', ['booking' => $booking->ref])
                ->with('error', 'Could not initiate payment. Please try again or use bank transfer.');

        } catch (\Throwable $e) {
            return redirect()
                ->route('checkout.show', ['booking' => $booking->ref])
                ->with('error', 'Payment gateway error. Please try again or contact us.');
        }
    }

    /**
     * Handle Paystack's redirect callback after payment.
     *
     * NO-KEYS PATH (default for this project):
     *   If PAYSTACK_SECRET_KEY is not configured, redirects to home gracefully.
     *
     * LIVE INTEGRATION PATH (requires .env keys):
     *   Verifies the transaction reference with Paystack's verify endpoint.
     *   On verified success, marks the booking as 'Confirmed' and redirects
     *   to the booking success page. On failure, redirects back with an error.
     */
    public function callback(Request $request)
    {
        // LIVE INTEGRATION: requires PAYSTACK_SECRET_KEY in .env
        $secret    = config('services.paystack.secret');
        $reference = $request->query('reference');

        if (empty($secret)) {
            // No-keys path: redirect gracefully without error.
            return redirect()->route('home')->with('status', 'Payment gateway is not configured.');
        }

        // ── LIVE PATH ──────────────────────────────────────────────────────────
        // LIVE INTEGRATION: verify the transaction reference against Paystack's API.
        if (empty($reference)) {
            return redirect()->route('home')->with('error', 'No payment reference received.');
        }

        try {
            $baseUrl = config('services.paystack.base_url');

            // LIVE INTEGRATION: this HTTP call verifies the transaction with Paystack.
            $response = Http::withToken($secret)
                ->get("{$baseUrl}/transaction/verify/{$reference}");

            if ($response->successful() && $response->json('data.status') === 'success') {
                // Find the booking by reference and mark it as Confirmed.
                $booking = Booking::where('ref', $reference)->first();

                if ($booking) {
                    $booking->update(['status' => 'Confirmed']);
                    return redirect()->route('booking.success', ['booking' => $booking->ref]);
                }

                return redirect()->route('home')->with('error', 'Booking not found for this payment.');
            }

            // Payment was not successful.
            return redirect()->route('home')->with('error', 'Payment could not be verified. Please contact us.');

        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', 'Payment verification error. Please contact us.');
        }
    }
}
