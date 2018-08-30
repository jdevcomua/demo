<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Models\Animal;
use App\Models\AnimalsRequest;
use App\User;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    public function getBreeds($species)
    {
        $breeds = $species->breeds->pluck('name', 'id')->toArray();

        $breeds = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $breeds, array_keys($breeds));

        return response(json_encode($breeds));
    }

    public function getColors($species)
    {
        $colors = $species->colors->pluck('name', 'id')->toArray();

        $colors = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $colors, array_keys($colors));

        return response(json_encode($colors));
    }

    public function getFurs($species)
    {
        $furs = $species->furs->pluck('name', 'id')->toArray();

        $furs = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $furs, array_keys($furs));

        return response(json_encode($furs));
    }

    public function getUsers()
    {
        $users = User::all()->pluck('name', 'id')->toArray();

        $users = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $users, array_keys($users));

        return response(json_encode($users));
    }

    public function badgeSearch(Request $request)
    {
        $badge = $request->get('badge');
        $animal = Animal::where('badge', $badge)
            ->whereNull('user_id')
            ->first();
        if (!$animal) {
            return response()->json([
                'message' => 'not found'
            ],400);
        }
        $animal->breed_text = $animal->breed->name;
        $animal->color_text = $animal->color->name;
        $animal->species_text = $animal->species->name;
        $animal->birthday_text = Date::getlocalizedDate($animal->birthday);
        return response()->json(['animal' => $animal]);
    }

    public function requestAnimal(Request $request) {
        if ($request->has('animal_id')) {
            $animal = Animal::findOrFail($request->get('animal_id'));
            if ($animal) {
                $isset = AnimalsRequest::where('animal_id', '=', $animal->id)
                    ->where('user_id', '=', \Auth::id())
                    ->where('processed', '=', 0)
                    ->count();
                if ($isset) {
                    return response()->json([
                        'message' => 'already'
                    ]);
                } else {
                    $animalRequest = new AnimalsRequest();
                    $animalRequest->user_id = \Auth::id();
                    $animalRequest->animal_id = $animal->id;
                    $animalRequest->save();
                    return response()->json([
                        'message' => 'success'
                    ]);
                }
            } else {
                abort(404);
            }
        }
        return response()->json([
            'message' => 'error'
        ], 400);
    }
}
