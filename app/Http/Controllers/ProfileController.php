<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __invoke()
    {
        $user = \Auth::user();

        return view('profile', [
            'user' => $user
        ]);
    }

}
