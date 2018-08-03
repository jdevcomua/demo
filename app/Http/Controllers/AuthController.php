<?php

namespace App\Http\Controllers;

use App\Auth\KyivIdUserResolver;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    
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

        if (!$user) return redirect()->route('bad-login');

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
