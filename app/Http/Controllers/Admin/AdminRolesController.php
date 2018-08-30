<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Models\Permission;
use App\Models\Role;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AdminRolesController extends Controller
{
    private $rolesModel;
    private $request;

    public function __construct(Role $rolesModel, Request $request)
    {
        $this->rolesModel = $rolesModel;
        $this->request = $request;
    }

    public function index()
    {
        return view('admin.roles.index', [
            'permissions' => Permission::get()
        ]);
    }

    public function rolesData(Request $request)
    {
        $model = new Role();

        $query = $model->newQuery()
            ->leftJoin('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->leftJoin('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('users', 'role_user.user_id', '=', 'users.id')
            ->groupBy('roles.id');

        $aliases = [
            'permissions_count' => 'COUNT(DISTINCT `permissions`.`id`)',
            'users_count' => 'COUNT(DISTINCT `users`.`id`)',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'display_name', 'permission']);

        $validator = Validator::make($data, [
            'name' => 'required|string|regex:/^[a-z-_]+$/|max:256',
            'display_name' => 'required|string|max:256',
            'permission' => 'required|array',
            'permission.*' => 'required|integer|exists:permissions,id',
        ], [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.max' => 'Назва має бути менше :max символів',
            'name.regex' => 'Назва може містити лише латинські символи та знаки \'-\' і \'_\'',
            'display_name.required' => 'Назва для відображення є обов\'язковим полем',
            'display_name.max' => 'Назва для відображення має бути менше :max символів',
            'permission.required' => 'Роль повинна мати хоча б один дозвіл',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'role')
                ->withInput();
        }

        $role = new Role();
        $role->name = $data['name'];
        $role->display_name = $data['display_name'];
        $role->save();
        $role->permissions()->attach($data['permission']);
        Cache::flush();

        return redirect()
            ->back()
            ->with('success_role', 'Роль додана успішно !');
    }

    public function edit($id)
    {
        $role = $this->rolesModel
            ->where('id', '=', $id)
            ->firstOrFail();

        $role->load('permissions');
        $role_permissions = $role->permissions
            ->pluck('name', 'id')
            ->toArray();
        Cache::flush();

        return view('admin.roles.edit', [
            'permissions' => Permission::get(),
            'role' => $role,
            'role_permissions' => $role_permissions,
        ]);
    }

    public function update($id)
    {
        /** @var \App\Models\Role $role */
        $role = $this->rolesModel
            ->where('id', '=', $id)
            ->firstOrFail();

        $data = $this->request->only(['name', 'display_name', 'permission']);

        $validator = Validator::make($data, [
            'name' => 'required|string|regex:/^[a-z-_]+$/|max:256',
            'display_name' => 'required|string|max:256',
            'permission' => 'required|array',
            'permission.*' => 'required|integer|exists:permissions,id',
        ], [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.max' => 'Назва має бути менше :max символів',
            'name.regex' => 'Назва може містити лише латинські символи та знаки \'-\' і \'_\'',
            'display_name.required' => 'Назва для відображення є обов\'язковим полем',
            'display_name.max' => 'Назва для відображення має бути менше :max символів',
            'permission.required' => 'Роль повинна мати хоча б один дозвіл',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'role')
                ->withInput();
        }

        $role->name = $data['name'];
        $role->display_name = $data['display_name'];
        $role->save();
        $role->permissions()->detach();
        $role->permissions()->attach($data['permission']);
        Cache::flush();

        return redirect()
            ->route('admin.roles.index');
    }

    public function remove($id)
    {
        $role = $this->rolesModel
            ->where('id', '=', $id)
            ->firstOrFail();

        $count = $role->users()->count();
        if ($count) {
            return redirect()
                ->back()
                ->withErrors([
                    'err' => 'Неможливо видалити роль. Кількість користувачів що її мають: ' . $count,
                ], 'role_rem');
        }

        $role->delete();
        Cache::flush();

        return redirect()
            ->back()
            ->with('success_role_rem', 'Роль видалена успішно !');
    }
}
