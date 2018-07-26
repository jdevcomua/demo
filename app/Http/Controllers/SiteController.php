<?php

namespace App\Http\Controllers;

use App\Auth\KyivIdUserResolver;
use Auth;
use Socialite;

class SiteController extends Controller
{

    public function __invoke()
    {
        if (\Auth::user() !== null) {
            return redirect('/pets');
        } else {
            return redirect('/about');
        }
    }

    public function login()
    {
        return Socialite::driver('kyivID')->redirect();
    }

    public function loginCallback()
    {
        $user = KyivIdUserResolver::resolve(
            Socialite::driver('kyivID')->user()
        );

        Auth::login($user);

        return redirect('/', 302);
    }

}
