<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function show()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        dd($request);
    }

}
