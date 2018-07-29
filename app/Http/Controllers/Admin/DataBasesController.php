<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\Species;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataBasesController extends Controller
{

    private $userModel;
    private $animalModel;

    public function __construct(User $userModel, Animal $animalModel)
    {
        $this->userModel = $userModel;
        $this->animalModel = $animalModel;
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

            $response["recordsTotal"] = $this->userModel->count();
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

    public function animalIndex()
    {
        return view('admin.db.animals', [
            'species' => Species::get()
        ]);
    }

    public function animalData(Request $request)
    {
        $filtered = false;

        $aliases = [
            'species_name' => 'species.name',
            'breeds_name' => 'breeds.name',
            'colors_name' => 'colors.name',
            'owner_name' => 'CONCAT(`users1`.last_name, \' \', `users1`.first_name, \'||\', `users1`.id)',
            'verified_name' => 'CONCAT(`users2`.last_name, \' \', `users2`.first_name, \'||\', `users2`.id)'
        ];
        $table = 'animals';

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            $query = $this->animalModel
                ->newQuery()
                ->select([
                    'animals.*',
                    'species.name as species_name',
                    'breeds.name as breeds_name',
                    'colors.name as colors_name',
                    DB::raw("CONCAT(`users1`.last_name, ' ', `users1`.first_name, '||', `users1`.id) AS `owner_name`"),
                    DB::raw("CONCAT(`users2`.last_name, ' ', `users2`.first_name, '||', `users2`.id) AS `verified_name`"),
                ])
                ->join('species', 'species.id', '=', 'animals.species_id')
                ->join('breeds', 'breeds.id', '=', 'animals.breed_id')
                ->join('colors', 'colors.id', '=', 'animals.color_id')
                ->join('users as users1', 'users1.id', '=', 'animals.user_id')
                ->leftJoin('users as users2', 'users2.id', '=', 'animals.confirm_user_id');

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
                        $query->orderBy(
                            $columns[$req['order'][0]['column']]['data'],
                            $req['order'][0]['dir']
                        );
                    } catch (\Exception $exception) {}
                }
            }

            $response['draw'] = +$req['draw'];

            $response["recordsTotal"] = $this->animalModel->count();
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
