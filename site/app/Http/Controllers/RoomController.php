<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('is_active', true)->orderBy('sort')->get();

        return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $similar = Room::where('is_active', true)
            ->where('id', '!=', $room->id)
            ->orderBy('sort')
            ->take(3)
            ->get();

        return view('rooms.show', compact('room', 'similar'));
    }
}
