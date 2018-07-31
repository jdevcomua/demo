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
        $query = User::whereNull('banned_at')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->groupBy('users.id')
        ;

        $aliases = [
            'role_names' =>'COALESCE(GROUP_CONCAT(roles.display_name), "")'
        ];

        $response = DataTables::provide($request, new User(), $query, $aliases);

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
        $query = User::whereNotNull('banned_at');
        $response = DataTables::provide($request, new User(), $query);

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
