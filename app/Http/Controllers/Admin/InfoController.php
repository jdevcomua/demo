<?php

namespace App\Http\Controllers\Admin;

use App\Models\Breed;
use App\Models\Color;
use App\Models\Species;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class InfoController extends Controller
{
    private $breedModel;
    private $colorModel;

    public function __construct(Breed $breedModel, Color $colorModel, Species $speciesModel)
    {
        $this->breedModel = $breedModel;
        $this->colorModel = $colorModel;
    }

    public function directoryIndex()
    {
        return view('admin.info.directory', [
            'species' => Species::get()
        ]);
    }

    public function directoryDataBreed(Request $request)
    {
        $filtered = false;

        $aliases = [
            'species_name' => 'species.name',
        ];
        $table = 'breeds';

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            $query = $this->breedModel
                ->newQuery()
                ->select([
                    'breeds.*',
                    'species.name as species_name',
                ])
                ->join('species', 'species.id', '=', 'breeds.species_id');

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

            $response["recordsTotal"] = $this->breedModel->count();
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

    public function directoryStoreBreed(Request $request)
    {
        if($request->has(['b_species', 'b_name', 'b_fci'])) {
            $data = $request->only(['b_species', 'b_name', 'b_fci']);

            $validator = Validator::make($data, [
                'b_species' => 'required|integer|exists:species,id',
                'b_name' => 'required|string|max:256',
                'b_fci' => 'nullable|string|size:3',
            ], [
                'b_name.required' => 'Назва є обов\'язковим полем',
                'b_name.max' => 'Назва має бути менше :max символів',
                'b_fci.size' => 'FCI повинен бути довжиною в :size символа',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'breed')
                    ->withInput();
            }

            $breed = new Breed();
            $breed->species_id = $data['b_species'];
            $breed->name = $data['b_name'];
            $breed->fci = $data['b_fci'];
            $breed->save();

            return redirect()
                ->back()
                ->with('success_breed', 'Порода додана успішно !');
        }
        return response('', 400);
    }

    public function directoryRemoveBreed(Request $request)
    {
        if ($request->has('id')) {
            $breed = $this->breedModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $breed->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити породу. Кількість тварин що її мають: ' . $count,
                    ], 'breed_rem');
            }

            $breed->delete();

            return redirect()
                ->back()
                ->with('success_breed_rem', 'Порода видалена успішно !');
        }
        return response('', 400);
    }

    public function directoryDataColor(Request $request)
    {
        $filtered = false;

        $aliases = [
            'species_name' => 'species.name',
        ];
        $table = 'colors';

        if ($request->has(['draw', 'start', 'length'])) {
            $req = $request->all();

            $query = $this->colorModel
                ->newQuery()
                ->select([
                    'colors.*',
                    'species.name as species_name',
                ])
                ->join('species', 'species.id', '=', 'colors.species_id');

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

            $response["recordsTotal"] = $this->colorModel->count();
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

    public function directoryStoreColor(Request $request)
    {
        if($request->has(['c_species', 'c_name'])) {
            $data = $request->only(['c_species', 'c_name']);

            $validator = Validator::make($data, [
                'c_species' => 'required|integer|exists:species,id',
                'c_name' => 'required|string|max:256',
            ], [
                'c_name.required' => 'Назва є обов\'язковим полем',
                'c_name.max' => 'Назва має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'color')
                    ->withInput();
            }

            $color = new Color();
            $color->species_id = $data['c_species'];
            $color->name = $data['c_name'];
            $color->save();

            return redirect()
                ->back()
                ->with('success_color', 'Масть додана успішно !');
        }
        return response('', 400);
    }

    public function directoryRemoveColor(Request $request)
    {
        if ($request->has('id')) {
            $color = $this->colorModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $color->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити масть. Кількість тварин що її мають: ' . $count,
                    ], 'color_rem');
            }

            $color->delete();

            return redirect()
                ->back()
                ->with('success_color_rem', 'Масть видалена успішно !');
        }
        return response('', 400);
    }
}
