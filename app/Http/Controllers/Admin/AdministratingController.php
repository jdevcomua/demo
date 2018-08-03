<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdministratingController extends Controller
{
    public function users()
    {
        return view('admin.administrating.users');
    }

    public function userData(Request $request)
    {
        $model = new User();

        $query = $model->newQuery()
            ->whereNull('banned_at')
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->groupBy('users.id');

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
            'role_names' =>'GROUP_CONCAT(`roles`.display_name SEPARATOR \'|\')',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned_at = Carbon::now();
        $user->banned_by = \Auth::id();
        $user->save();
        return redirect()
            ->back()
            ->with('success_user', 'Користувач успішно заблокований!');
    }

    public function bannedUsers()
    {
        return view('admin.administrating.banned');
    }

    public function bannedUsersData(Request $request)
    {
        $model = new User();

        $query = $model->newQuery()
            ->whereNotNull('banned_at')
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->groupBy('users.id');

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned_at = null;
        $user->banned_by = null;
        $user->save();
        return redirect()
            ->back()
            ->with('success_user', 'Користувач успішно розблокований!');
    }
}
