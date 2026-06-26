<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        $faqs = Faq::orderBy('sort')->get();

        return view('faq', compact('faqs'));
    }
}
