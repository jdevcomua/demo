<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Permission;
use App\Models\Role;

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

    public function faq()
    {
        return view('faq', [
            'questions' => Faq::all()
        ]);
    }

    public function test()
    {
        $admin = Role::find(1);

        $permission = Permission::where('name', 'block-user');
        if (!$permission) {

            $blockUser = new Permission();
            $blockUser->name = 'block-user';
            $blockUser->display_name = 'Заблокувати користувача';
            $blockUser->save();

            $admin->attachPermission($blockUser);
        }
        return redirect()->back();
    }

}
