<?php

namespace App\Http\Controllers;

use App\Auth\KyivIdUserResolver;
use App\Models\Log;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\InvalidStateException;
use Socialite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    public function login()
    {
        return Socialite::driver('kyivID')->login();
    }

    public function reLogin()
    {
        return Socialite::driver('kyivID')->login(true);
    }

    public function loginCallback(Request $request)
    {
        \RhaLogger::start([
            'code' => $request->get('code'),
            'state' => $request->get('state')
        ]);
        \RhaLogger::update(['action' => Log::ACTION_REGISTER]);

        try {
            $user = KyivIdUserResolver::resolve(
                Socialite::driver('kyivID')->user()
            );
        } catch (InvalidStateException $e) {
            return redirect()->route('login');
        } catch (\Throwable $e) {
            \RhaLogger::addError($e);
            throw $e;
        }

        if (!$user) {
            \RhaLogger::addPayload(['error' => 'BAD LOGIN - missing required data (passport, itin, address, phone)']);
            return redirect()->route('bad-login');
        }

        \RhaLogger::end(true);

        Auth::login($user);

        return redirect('/', 302);
    }

    public function loginAsAdmin()
    {
        if (!config('app.debug')) throw new NotFoundHttpException();

        Auth::login(\App\User::find(1));
        return redirect('/', 302);
    }
}
