<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Models\Breed;
use App\Models\Color;
use App\Models\Fur;
use App\Models\Species;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class InfoController extends Controller
{
    private $breedModel;
    private $colorModel;
    private $furModel;

    public function __construct(Breed $breedModel, Color $colorModel, Fur $furModel)
    {
        $this->breedModel = $breedModel;
        $this->colorModel = $colorModel;
        $this->furModel = $furModel;
    }

    public function directoryIndex()
    {
        return view('admin.info.directory', [
            'species' => Species::get()
        ]);
    }

    public function directoryDataBreed(Request $request)
    {
        $model = new Breed();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'breeds.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

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
        $model = new Color();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'colors.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

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

    public function directoryDataFur(Request $request)
    {
        $model = new Fur();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'furs.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreFur(Request $request)
    {
        if($request->has(['f_species', 'f_name'])) {
            $data = $request->only(['f_species', 'f_name']);

            $validator = Validator::make($data, [
                'f_species' => 'required|integer|exists:species,id',
                'f_name' => 'required|string|max:256',
            ], [
                'f_name.required' => 'Назва є обов\'язковим полем',
                'f_name.max' => 'Назва має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'fur')
                    ->withInput();
            }

            $color = new Fur();
            $color->species_id = $data['f_species'];
            $color->name = $data['f_name'];
            $color->save();

            return redirect()
                ->back()
                ->with('success_fur', 'Тип шерсті додано успішно !');
        }
        return response('', 400);
    }

    public function directoryRemoveFur(Request $request)
    {
        if ($request->has('id')) {
            $fur = $this->furModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $fur->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити тип шерсті. Кількість тварин що її мають: ' . $count,
                    ], 'fur_rem');
            }

            $fur->delete();

            return redirect()
                ->back()
                ->with('success_fur_rem', 'Тип шерсті видалено успішно !');
        }
        return response('', 400);
    }
}
