<?php

namespace App\Http\Controllers;

use App\Models\UserEmail;
use App\Models\UserPhone;
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

    public function addPhone(Request $request)
    {
        $user = \Auth::user();

        $data = $request->only('phone');

        $validator = \Validator::make($data,[
            'phone' => 'required|min:6|max:20'
        ],[
            'phone.required' => 'Номер телефону є обов\'язковим полем!',
            'phone.min' => 'Номер телефону має бути більше ніж :min символів',
            'phone.max' => 'Номер телефону має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'phone')
                ->withInput();
        }

        $user->phones()->create([
            'user_id' => $user->id,
            'phone' => $data['phone'],
            'type' => UserPhone::TYPE_ADDITIONAL
        ]);

        return redirect()
            ->back()
            ->with('success_phone', 'Данні оновлено успішно!');

    }

    public function addEmail(Request $request)
    {
        $user = \Auth::user();

        $data = $request->only('email');

        $validator = \Validator::make($data,[
            'email' => 'required'
        ],[
            'email.required' => 'Email є обов\'язковим полем!',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'email')
                ->withInput();
        }

        $user->emails()->create([
            'user_id' => $user->id,
            'email' => $data['email'],
            'type' => UserEmail::TYPE_ADDITIONAL
        ]);

        return redirect()
            ->back()
            ->with('success_email', 'Данні оновлено успішно!');

    }

}
