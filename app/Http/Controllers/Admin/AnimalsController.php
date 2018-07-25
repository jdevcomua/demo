<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\Species;
use App\Models\Breed;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class AnimalsController extends Controller
{

    public function index()
    {
        return view('admin.animals.new');
    }

    public function getAnimalsNewData()
    {
        $query = Animal::whereNull('confirm_user_id')
            ->with(['user', 'breed', 'species', 'color'])
            ->select('animals.*');
        return Datatables::of($query)
            ->addColumn('action', function ($animal) {
                return '<a href="' . route('admin.animals.show', $animal->id) . '" class="btn accept btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Редагувати</a>';
            })->make(true);
    }

    public function archive()
    {
        return view('admin.animals.archive');
    }

    public function getAnimalsArchiveData()
    {
        $query = Animal::whereNotNull('confirm_user_id')
            ->with(['user', 'userThatConfirmed', 'breed', 'species', 'color'])
            ->select('animals.*');
        return Datatables::of($query)->make(true);
    }

    public function show($id) {
        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()
                ->back();
        }
        $animal->load('user', 'userThatConfirmed', 'breed', 'species', 'color', 'files');
        $species = Species::all();
        $breeds = Breed::all();

        return view('admin.animals.show',[
            'animal' => $animal
        ]);
    }


}
