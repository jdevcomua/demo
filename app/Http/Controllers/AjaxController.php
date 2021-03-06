<?php

namespace App\Http\Controllers;

use App\Events\AnimalBadgeRequestSent;
use App\Helpers\Date;
use App\Models\Animal;
use App\Models\AnimalsRequest;
use App\Models\IdentifyingDevice;
use App\Models\IdentifyingDeviceType;
use App\Models\Organization;
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

    public function getDeviceTypes()
    {
        $deviceTypes = IdentifyingDeviceType::all()->pluck('name', 'id')->toArray();

        $deviceTypes = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $deviceTypes, array_keys($deviceTypes));

        return response(json_encode($deviceTypes));
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

    public function getOrganizations()
    {
        $organizations = Organization::all()->pluck('name', 'id')->toArray();

        $organizations = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $organizations, array_keys($organizations));

        return response(json_encode($organizations));
    }

    public function badgeSearch(Request $request)
    {
        $badge = $request->get('badge');
        $badgeTypeId = IdentifyingDeviceType::where('name', 'Жетон')->firstOrFail()->id;
        $animal = IdentifyingDevice::where('identifying_device_type_id', $badgeTypeId)
            ->where('number', $badge)
            ->firstOrFail()
            ->animal()
            ->whereNull('user_id')
            ->first();
        
        if (!$animal) {
            return response()->json([
                'message' => 'not found'
            ],404);
        }
        $animal->breed_text = $animal->breed->name;
        $animal->color_text = $animal->color->name;
        $animal->species_text = $animal->species->name;
        $animal->birthday_text = Date::getlocalizedDate($animal->birthday);
        return response()->json(['animal' => $animal]);
    }

    public function requestAnimal(Request $request)
    {
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
                    $animalRequest->gender = $animal->gender;
                    $animalRequest->nickname = $animal->nickname;
                    $animalRequest->species_id = $animal->species_id;
                    $animalRequest->breed_id = $animal->breed_id;
                    $animalRequest->color_id = $animal->color_id;
                    $animalRequest->fur_id = $animal->fur_id;
                    $animalRequest->birthday = $animal->birthday;
                    $animalRequest->save();

                    event(new AnimalBadgeRequestSent($request->user(), [$animal]));

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
