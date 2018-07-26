<?php

namespace App\Auth;

use App\User;

class KyivIdUserResolver
{

    /**
     * @param \App\User $user
     * @return User|null
     */
    public static function resolve($user):? User
    {
        if (!$user) return null;

        //Searching by external kievID
        $existUser = User::where('ext_id', '=', $user->ext_id)->first();

        //if not found - search by inn
        if (!$existUser) {
            $existUser = User::where('inn', '=', $user->inn)->first();
        }

        //if not found - search by passport
        if (!$existUser) {
            $existUser = User::where('passport', '=', $user->passport)->first();
        }

        if ($existUser) {
            $existUser = self::update($existUser, $user);
        } else {
            $existUser = self::register($user);
        }

        return $existUser;
    }

    /**
     * @param \App\User $existing
     * @param \App\User $user
     * @return \App\User|null
     */
    private static function update($existing, $user):? User
    {
        $existing->update($user->attributesToArray());
        return $existing;
    }

    /**
     * @param \App\User $user
     * @return \App\User|null
     */
    private static function register($user):? User
    {
        $user->save();
        return $user;
    }
}