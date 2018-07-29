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
            return redirect()->route('animals.index');
        } else {
            return redirect()->route('about');
        }
    }

    public function login()
    {
        return Socialite::driver('kyivID')->redirect();
    }

    public function loginAttempt()
    {
        return Socialite::driver('kyivID')->attempt();
    }

    public function loginCallback()
    {
        $user = KyivIdUserResolver::resolve(
            Socialite::driver('kyivID')->user()
        );

        Auth::login($user);

        return redirect('/', 302);
    }


    public function loginAsAdmin()
    {
        Auth::login(\App\User::find(1));
        return redirect('/', 302);
    }

    public function loginAsUser()
    {
        Auth::login(\App\User::find(2));
        return redirect('/', 302);
    }

}
