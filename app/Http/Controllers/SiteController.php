<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Cache;

class SiteController extends Controller
{

    public function __invoke()
    {
        if (\Auth::user() !== null) {
            return redirect()->route('animals.index');
        } else {
            return redirect()->route('about');
        }
    }

    public function faq()
    {
        $faqs = Cache::tags('faq')
            ->remember('faq', 1000, function()
        {
            return Faq::all();
        });
        return view('faq', [
            'questions' => $faqs
        ]);
    }

}
