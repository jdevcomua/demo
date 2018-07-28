<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataBasesController extends Controller
{

    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }


    public function userIndex()
    {
        return view('admin.db.users');
    }

    public function userData(Request $request)
    {
        $filtered = false;

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            $query = $this->userModel->newQuery();

            if ($request->has('columns')) {
                $columns = $req['columns'];
                if (is_array($columns)) {
                    foreach ($columns as $column) {
                        try {
                            if ($column['search']['value'] !== null) {
                                $query->where($column['data'], 'like',
                                    '%' . $column['search']['value'] . '%');
                                $filtered = true;
                            }
                        } catch (\Exception $exception) {}
                    }
                }
                if ($request->has('order')) {
                    try {
                        $query->orderBy(
                            $columns[$req['order'][0]['column']]['data'],
                            $req['order'][0]['dir']
                        );
                    } catch (\Exception $exception) {}
                }
            }

            $response['draw'] = +$req['draw'];

            $response["recordsTotal"] = User::count();
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

}
