<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use DB;
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
        $filtered = false;

        $aliases = [
            'permissions_count' => 'COUNT(`permissions`.`id`)',
            'users_count' => 'COUNT(`users`.`id`)',
        ];
        $table = 'roles';

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            $query = $this->rolesModel
                ->newQuery()
                ->select([
                    'roles.*',
                    DB::raw('COUNT(`permissions`.`id`) AS permissions_count'),
                    DB::raw('COUNT(`users`.`id`) AS users_count'),
                ])
                ->leftJoin('permission_role', 'roles.id', '=', 'permission_role.role_id')
                ->leftJoin('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('users', 'role_user.user_id', '=', 'users.id')
                ->groupBy('roles.id');


            if ($request->has('columns')) {
                $columns = $req['columns'];
                if (is_array($columns)) {
                    foreach ($columns as $column) {
                        try {
                            if ($column['search']['value'] !== null) {
                                if (!array_key_exists($column['data'], $aliases)) {
                                    $query->where($table. '.' .$column['data'], 'like',
                                        '%' . $column['search']['value'] . '%');
                                } else {
                                    $query->whereRaw($aliases[$column['data']] . ' like '
                                        . '\'%' . $column['search']['value'] . '%\''
                                    );
                                }
                                $filtered = true;
                            }
                        } catch (\Exception $exception) {}
                    }
                }
                if ($request->has('order')) {
                    try {
                        if (!array_key_exists($columns[$req['order'][0]['column']]['data'], $aliases)) {
                            $query->orderBy(
                                $table. '.' .$columns[$req['order'][0]['column']]['data'],
                                $req['order'][0]['dir']
                            );
                        } else {
                            $query->orderBy(
                                $columns[$req['order'][0]['column']]['data'],
                                $req['order'][0]['dir']
                            );
                        }
                    } catch (\Exception $exception) {}
                }
            }

            $response['draw'] = +$req['draw'];

            $response["recordsTotal"] = $this->rolesModel->count();
            if ($filtered) {
                $response["recordsFiltered"] = $query->count();
            } else {
                $response["recordsFiltered"] = $response["recordsTotal"];
            }

            $response['data'] = $query->offset($req['start'])
                ->limit($req['length'])
                ->get()
                ->toArray();

            return response()->json($response);
        }
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

        return redirect()
            ->back()
            ->with('success_role_rem', 'Роль видалена успішно !');
    }
}
