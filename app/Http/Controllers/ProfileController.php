<?php

namespace App\Http\Controllers;

use App\Models\UserEmail;
use App\Models\UserPhone;
use App\Rules\Phone;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function show()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = \Auth::user();
        $data = $request->only(['phone', 'email']);

        $validator = \Validator::make($data,[
            'phone' => [
                'nullable',
                'min:6',
                'max:20',
                new Phone
            ],
            'email' => 'nullable|email'
        ],[
            'phone.min' => 'Номер телефону має бути більше ніж :min символів',
            'phone.max' => 'Номер телефону має бути менше :max символів',
            'email.email' => 'Поштова адреса введена некоректно',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'profile')
                ->withInput();
        }

        $phone = $user->phonesAdditional()->first();
        $email = $user->emailsAdditional()->first();

        $phoneData = [
            'phone' => $data['phone'] ?? '',
            'type' => UserPhone::TYPE_MANUAL
        ];
        $emailData = [
            'email' => $data['email'] ?? '',
            'type' => UserEmail::TYPE_MANUAL
        ];

        if ($phone) {
            $phone->update($phoneData);
        } else {
            $user->phones()->create($phoneData);
        }

        if ($email) {
            $email->update($emailData);
        } else {
            $user->emails()->create($emailData);
        }

        return redirect()
            ->back()
            ->with('success_profile', 'Дані оновлено успішно!');
    }

}
