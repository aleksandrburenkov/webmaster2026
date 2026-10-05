<?php

namespace App\Http\Controllers;

class LegalPageController extends Controller
{
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function offer()
    {
        return view('pages.offer');
    }
}
