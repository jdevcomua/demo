<?php

namespace App\Http\Controllers\Admin\Requests;

use App\Helpers\DataTables;
use App\Models\LostAnimal;
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
            ->groupBy('id');

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
}
