<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->get();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        // Mark as read on first view
        if (is_null($message->read_at)) {
            $message->update(['read_at' => now()]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
                         ->with('status', 'Message deleted successfully.');
    }
}
