<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;

class CheckForVerifiedAnimals
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        if ($user) {
            $animals = $user->animals()->where('verified', 0)->get();
            $count = $animals->count();
            if ($count) {
                $notification = Notification::where('min', '>=', $count)->where('type', Notification::TYPE_NOT_VERIFIED)->first();
                $text = str_replace('{кількість}', $count, $notification->text);
                if ($count === 1) {
                    $text = str_replace('{ім\'я}', $animals->first()->name, $text);
                }
                session()->put('notification', $text);
            }
        }
        return $next($request);
    }
}
