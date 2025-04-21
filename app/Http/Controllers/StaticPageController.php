<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function aboutUs()
    {
        return view('pages.static_pages.about_us');
    }

    public function contactUs()
    {
        return view('pages.static_pages.contact_us');
    }

    public function faq()
    {
        $faqs = Faq::all();
        return view('pages.static_pages.faq', compact('faqs'));
    }
}