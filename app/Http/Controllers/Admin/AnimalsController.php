<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Models\Species;
use App\Models\Breed;
use App\Models\Color;
use App\Rules\BirthdayRule;
use App\User;
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
        return Datatables::of($query)
            ->addColumn('action', function ($animal) {
                return '<a href="' . route('admin.animals.show', $animal->id) . '" class="btn accept btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Редагувати</a>';
            })->make(true);
    }

    public function show($id) {
        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()
                ->back();
        }
        $animal->load('user', 'userThatConfirmed', 'breed', 'species', 'color', 'files');
        $species = Species::all();
        $users = User::all();

        return view('admin.animals.show',[
            'animal' => $animal,
            'species' => $species,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            "nickname",
            "species_id",
            "breed_id",
            "color_id",
            "gender",
            "birthday",
            "sterilized",
            "user_id",
            "comment",
        ]);

        $validator = \Validator::make($data,[
            'nickname' => 'required|string|max:255',
            'species_id' => 'required|integer|exists:species,id',
            'breed_id' => 'required|integer|exists:breeds,id',
            'color_id' => 'required|integer|exists:colors,id',
            'birthday' => [
                'nullable',
                'date',
                'max:255',
                new BirthdayRule
            ],
            'gender' => 'nullable|integer|min:0|max:1',
            'sterilized' => 'integer|max:1|min:0',
            'user_id' => 'required|integer|exists:users,id',
            'comment' => 'nullable|string|max:1000',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()->back();
        }

        $animal->update($data);
        $animal->save();
        return redirect()
            ->back();

    }

    public function confirm($id){
        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()->back();
        }
        $animal->confirm_user_id = \Auth::user()->id;
        $animal->save();
        return redirect()->route('admin.animals.archive');
    }


}
