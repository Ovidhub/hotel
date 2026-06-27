<?php

namespace App\Http\Controllers;

use App\Models\Apartment;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::where('is_active', true)->orderBy('sort')->get();

        return view('apartments.index', compact('apartments'));
    }

    public function show(Apartment $apartment)
    {
        return view('apartments.show', compact('apartment'));
    }
}
