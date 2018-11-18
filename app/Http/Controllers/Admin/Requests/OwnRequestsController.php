<?php

namespace App\Http\Controllers\Admin\Requests;

use App\Events\AnimalRequestAccepted;
use App\Events\AnimalRequestDeclined;
use App\Helpers\DataTables;
use App\Models\Animal;
use App\Models\AnimalsRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OwnRequestsController extends Controller
{

    public function index()
    {
        return view('admin.administrating.requests.own');
    }

    public function data(Request $request)
    {
        $model = new AnimalsRequest();

        $query = $model->newQuery()
            ->leftJoin('users', 'animals_requests.user_id', '=', 'users.id')
            ->leftJoin('animals', 'animals_requests.animal_id', '=', 'animals.id')
            ->leftJoin('breeds', 'animals_requests.breed_id', '=', 'breeds.id')
            ->leftJoin('species', 'animals_requests.species_id', '=', 'species.id')
            ->leftJoin('furs', 'animals_requests.fur_id', '=', 'furs.id')
            ->leftJoin('colors', 'animals_requests.color_id', '=', 'colors.id')
            ->groupBy('animals_requests.id');

        $aliases = [
            'user' => 'CONCAT(`users`.last_name, \' \', `users`.first_name)',
            'species_name' => 'species.name',
            'breed_name' => 'breeds.name',
            'fur_name' => 'furs.name',
            'color_name' => 'colors.name',
            'type' => '(`animals_requests`.`animal_id` IS NOT NULL)',
            'address' => 'CONCAT(
                 COALESCE(`animals_requests`.street, " "), ", " ,
                 COALESCE(`animals_requests`.building, " "), ", " ,
                 COALESCE(`animals_requests`.apartment, " ")
           )',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function confirm($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        $animal = Animal::findOrFail($animalRequest->animal_id);
        $animal->user_id = $animalRequest->user_id;
        $animal->save();

        $user = User::find($animalRequest->user_id);

        event(new AnimalRequestAccepted($user, [$animal]));

        return redirect()
            ->back()
            ->with('success_request', 'Запит було прийнято успішно');
    }

    public function proceed($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        return redirect()
            ->back()
            ->with('success_request', 'Запит було оброблено успішно');
    }

    public function cancel($id)
    {
        $animalRequest = AnimalsRequest::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        $user = User::find($animalRequest->user_id);

        event(new AnimalRequestDeclined($user, [$animalRequest->animal]));

        return redirect()
            ->back()
            ->with('success_request', 'Запит було відхилено успішно!');
    }
}
