<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** Show the site settings page. */
    public function edit()
    {
        return view('admin.settings', [
            'whatsappEnabled' => Setting::get('whatsapp_enabled', '1') === '1',
            'whatsappNumber'  => Setting::get('whatsapp_number', config('hotel.phone_href')),
            'whatsappMessage' => Setting::get('whatsapp_message', 'Hello Hotel Benizia, I would like to make an enquiry.'),
        ]);
    }

    /** Persist the WhatsApp / site settings. */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number'  => ['nullable', 'string', 'max:40', 'regex:/^[0-9+\-()\s]*$/'],
            'whatsapp_message' => ['nullable', 'string', 'max:280'],
        ], [
            'whatsapp_number.regex' => 'The WhatsApp number may only contain digits, spaces and + - ( ) characters.',
        ]);

        Setting::put('whatsapp_enabled', $request->boolean('whatsapp_enabled') ? '1' : '0');
        Setting::put('whatsapp_number', trim((string) $validated['whatsapp_number']));
        Setting::put('whatsapp_message', trim((string) ($validated['whatsapp_message'] ?? '')));

        return back()->with('status', 'Settings saved successfully.');
    }
}
