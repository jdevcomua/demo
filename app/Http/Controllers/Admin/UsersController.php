<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Rules\BirthdayRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function getUsersData()
    {
        $userData = User::leftJoin('animals', 'animals.user_id', '=', 'users.id')
            ->select([
                'users.*',
                \DB::raw('COALESCE(count(animals.id),0) as animals_count')
            ])
            ->groupBy('users.id');

        return \DataTables::of($userData)
            ->addColumn('action', function ($user) {
                return '<a href="' . route('admin.users.show', $user->id) . '" class="btn accept btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Редагувати</a>';
            })
            ->make(true);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }
        $user->load('animals');

        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'first_name',
            'last_name',
            'middle_name',
            'email',
            'phone',
            'birthday',
            'gender',
            'passport',
            'residence_address'
        ]);

        $validator = \Validator::make($data,[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'birthday' => [
                'nullable',
                'date',
                'max:255',
                new BirthdayRule
            ],
            'gender' => 'nullable|integer|min:0|max:1',
            'passport' => 'nullable|string|max:255',
            'residence_address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $data['birthday'] = Carbon::createFromFormat('d-m-Y',$data['birthday'])->format("Y-m-d H:i:s");

        $user = User::find($id);
        $user->update($data);
        $user->save();
        return redirect()->back();
    }

    public function administrate()
    {
        $roles = Role::all();
        return view('admin.users.administrate', [
            'roles' => $roles
        ]);
    }

    public function getUsersAdministrativeData()
    {

        $query = User::with('roles')->select('users.*');

        return \DataTables::eloquent($query)
            ->addColumn('roles', function (User $user) {
                return $user->roles->count() ? $user->roles->map(function($role) {
                    return $role->display_name;
                })->implode(', ') : 'Користувач';
            })
            ->addColumn('action', function ($user) {
                return '<a href="#" data-id="' . $user->id. '" data-name="' .( $user->first_name . ' ' .$user->last_name )
                .'" data-role="' . ($user->roles()->count() ? $user->roles[0]->id : 0) .
                    '" class="btn accept btn-xs btn-primary change-role" data-toggle="modal" data-target="#modal"><i class="glyphicon glyphicon-user"></i> Редагувати Роль</a>';
            })
            ->make(true);
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);
        $role = +($request->get('role'));
        $user->roles()->sync([]);
        $user->roles()->attach($role);
        return redirect()->back();
    }

    public function bans()
    {
        return view('admin.users.index');
    }

    public function getUsersBansData()
    {
        $userData = User::leftJoin('animals', 'animals.user_id', '=', 'users.id')
            ->select([
                'users.*',
                \DB::raw('COALESCE(count(animals.id),0) as animals_count')
            ])
            ->groupBy('users.id');

        return \DataTables::of($userData)
            ->addColumn('action', function ($user) {
                return '<a href="#" data-id="' . $user->id. '" data-name="' .( $user->first_name . ' ' .$user->last_name )
                    .'" class="btn accept btn-xs btn-primary change-role" data-toggle="modal" data-target="#modal"><i class="glyphicon glyphicon-ban-circle"></i> Забанити</a>';
            })
            ->make(true);
    }

}
