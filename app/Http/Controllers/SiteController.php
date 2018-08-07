<?php

namespace App\Http\Controllers;

use App\Models\Faq;

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
        return view('faq', [
            'questions' => Faq::all()
        ]);
    }

}
