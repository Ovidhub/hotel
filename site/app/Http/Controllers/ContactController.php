<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();

        // Persist only validated fields — read_at stays null (not mass-assigned)
        Message::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
        ]);

        // Notify the hotel — wrapped in try/catch so mail failure never blocks submission
        try {
            $name  = $data['name'];
            $email = $data['email'];
            Mail::raw(
                "New contact message from {$name} <{$email}>\n"
                . "Phone: " . ($data['phone'] ?? 'N/A') . "\n"
                . "Subject: " . ($data['subject'] ?? 'N/A') . "\n\n"
                . $data['message'],
                function ($m) use ($name, $email) {
                    $m->to(config('hotel.email'))
                      ->replyTo($email, $name)
                      ->subject('New Contact Message — Hotel Benizia');
                }
            );
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed: ' . $e->getMessage());
        }

        return redirect()->route('contact')
            ->with('status', 'Thank you for reaching out! Our team will respond shortly.');
    }
}
