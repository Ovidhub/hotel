<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function about()
    {
        $testimonials = Testimonial::orderBy('id')->take(6)->get();

        return view(theme_view('about'), compact('testimonials'));
    }

    public function faq()
    {
        $faqs = Faq::orderBy('sort')->get();

        return view(theme_view('faq'), compact('faqs'));
    }
}
