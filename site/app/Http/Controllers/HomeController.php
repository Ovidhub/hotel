<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $rooms        = Room::where('is_active', true)->orderBy('sort')->get();
        $testimonials = Testimonial::orderBy('sort')->get();

        return view('home', compact('rooms', 'testimonials'));
    }
}
