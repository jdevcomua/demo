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
        $response = DataTables::provide($request, new User());

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
            ->with('success_user', 'Користувач успішно забанен!');
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
            ->with('success_user', 'Користувач успішно разбанен!');
    }
}
