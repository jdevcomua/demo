<?php

namespace App\Http\Controllers\Admin\Requests;

use App\Events\AnimalFoundApproved;
use App\Events\AnimalFoundDeclined;
use App\Helpers\DataTables;
use App\Models\Breed;
use App\Models\Color;
use App\Models\FoundAnimal;
use App\Models\LostAnimal;
use App\Models\Species;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LostRequestsController extends Controller
{

    public function index()
    {
        return view('admin.administrating.requests.lost');
    }

    public function data(Request $request)
    {
        $model = new LostAnimal();

        $query = $model->newQuery()
            ->leftJoin('animals', 'animals.id', '=', 'lost_animals.animal_id')
            ->groupBy('id')
            ->orderBy('created_at', 'desc');

        $aliases = [
            'nickname' => '`animals`.nickname',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function proceed($id)
    {
        $animalRequest = LostAnimal::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        return redirect()
            ->back()
            ->with('success_request', 'Запит було оброблено успішно');
    }

    public function foundIndex()
    {
        return view('admin.administrating.requests.found', [
            'species' => Species::all(),
            'breeds' => Breed::all(),
            'colors' => Color::all(),
        ]);
    }

    public function foundData(Request $request)
    {
        $model = new FoundAnimal();

        $query = $model->newQuery()
            ->leftJoin('species', 'species.id', '=', 'found_animals.species_id')
            ->leftJoin('breeds', 'breeds.id', '=', 'found_animals.breed_id')
            ->leftJoin('colors', 'colors.id', '=', 'found_animals.color_id')
            ->groupBy('id')
            ->orderBy('created_at', 'desc');

        $aliases = [
            'breed' => '`breeds`.name',
            'color' => '`colors`.name',
            'species' => '`species`.name',
        ];


        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function foundShow($id)
    {
        $animal = FoundAnimal::findOrFail($id);

        return view('admin.administrating.requests.found_show', [
            'animal' => $animal,
        ]);
    }

    public function foundProceed($id)
    {
        $animalRequest = FoundAnimal::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        return redirect()
            ->back()
            ->with('success_animal_processed', 'Запит було оброблено успішно');
    }

    public function foundApprove($id)
    {
        $animalRequest = FoundAnimal::findOrFail($id);
        $animalRequest->approved = 1;
        $animalRequest->save();

        if ($animalRequest->contact_email !== null) {
            event(new AnimalFoundApproved($animalRequest, [$animalRequest]));
        }

        return redirect()
            ->back()
            ->with('success_animal_approve', 'Запит було оброблено успішно');
    }

    public function foundDisapprove($id)
    {
        $animalRequest = FoundAnimal::findOrFail($id);
        $animalRequest->approved = 0;
        $animalRequest->save();

        if ($animalRequest->contact_email !== null) {
            event(new AnimalFoundDeclined($animalRequest, [$animalRequest]));
        }

        return redirect()
            ->back()
            ->with('success_animal_approve', 'Запит було оброблено успішно');
    }
}
