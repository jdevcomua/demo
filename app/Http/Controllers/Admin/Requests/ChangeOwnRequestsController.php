<?php

namespace App\Http\Controllers\Admin\Requests;

use App\Helpers\DataTables;
use App\Models\ChangeAnimalOwner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChangeOwnRequestsController extends Controller
{

    public function index()
    {
        return view('admin.administrating.requests.change_own');
    }


    public function data(Request $request)
    {
        $model = new ChangeAnimalOwner();

        $query = $model->newQuery()
            ->leftJoin('animals', 'change_animal_owners.animal_id', '=', 'animals.id')
            ->groupBy('change_animal_owners.id');

        $aliases = [
            'nickname' => 'animals.nickname'
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function proceed($id)
    {
        $animalRequest = ChangeAnimalOwner::findOrFail($id);
        $animalRequest->processed = 1;
        $animalRequest->save();

        return redirect()
            ->back()
            ->with('success_request', 'Запит було оброблено успішно');
    }
}
