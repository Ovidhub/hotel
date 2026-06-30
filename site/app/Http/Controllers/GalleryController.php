<?php

namespace App\Http\Controllers;

use App\Models\Apartment;

class GalleryController extends Controller
{
    public function index()
    {
        $apartments = Apartment::where('is_active', true)->orderBy('sort')->get();

        return view('gallery', compact('apartments'));
    }
}
