<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\Species;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Validator;

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

    public function animalEdit($id)
    {
        $animal = $this->animalModel
            ->where('id', '=', $id)
            ->firstOrFail();

        $animal->load('files');

        return view('admin.db.animals_edit', [
            'animal' => $animal,
            'species' => Species::get()
        ]);
    }

    public function animalUpdate(Request $request, $id)
    {
        $animal = $this->animalModel
            ->where('id', '=', $id)
            ->firstOrFail();

        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'birthday',
            'sterilized', 'comment', 'images', 'documents']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'birthday' => 'required|date',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000'
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'comment.max' => 'Коментарій має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'animal')
                ->withInput();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);

        $data['sterilized'] = array_key_exists('sterilized', $data);

        $animal->fill($data);
        $animal->save();

        return redirect()
            ->back()
            ->with('success_animal', 'Данні оновлено успішно !');
    }

    public function animalVerify(Request $request, $id) {
        if ($request->has('state')) {
            $animal = $this->animalModel
                ->findOrFail($id);
            $state = +$request->get('state');
            if ($state === 0 || $state === 1) {
                $animal->verified = $state;
                $animal->confirm_user_id = ($state === 1) ? \Auth::id() : null;
                $animal->save();
                return redirect()->back();
            }
            return response('', 422);
        }
        return response('', 400);
    }

}
