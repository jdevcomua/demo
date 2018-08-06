<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Models\Animal;
use App\Models\AnimalsFile;
use App\Models\Role;
use App\Models\Species;
use App\Services\FilesService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Validator;

class DataBasesController extends Controller
{
    private $filesService;
    private $animalModel;

    public function __construct(Animal $animalModel, FilesService $filesService)
    {
        $this->animalModel = $animalModel;
        $this->filesService = $filesService;
    }


    public function userIndex()
    {
        return view('admin.db.users');
    }

    public function userData(Request $request)
    {
        $model = new User();

        $query = $model->newQuery()
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->groupBy('users.id');

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function userShow($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $user->load('animals', 'roles');
        return view('admin.db.users_show', compact('user', 'roles'));
    }

    public function userUpdate(Request $request, $id)
    {
        return redirect()->back();
    }

    public function userUpdateRoles(Request $request, $id)
    {
        $data = $request->only('roles');

        $validator = \Validator::make($data, [
            'roles' => 'nullable|array',
            'roles.*' => 'exists:users,id', // check each item in the array
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'user_roles');
        }

        if (!array_key_exists('roles', $data)) $data['roles'] = array();

        $user = User::findOrFail($id);
        $user->roles()->sync($data['roles']);

        return redirect()
            ->back()
            ->with('success_user_roles', 'Ролі успішно змінені!');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        foreach ($user->animals as $animal) {
            $animal->delete();
        }
        $user->delete();
        return redirect()
            ->back()
            ->with('success_user', 'Користувач успішно видалений!');
    }

    public function animalIndex()
    {
        return view('admin.db.animals', [
            'species' => Species::get()
        ]);
    }

    public function animalData(Request $request)
    {
        $model = new Animal();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'animals.species_id')
            ->join('breeds', 'breeds.id', '=', 'animals.breed_id')
            ->join('colors', 'colors.id', '=', 'animals.color_id')
            ->join('users as users1', 'users1.id', '=', 'animals.user_id')
            ->leftJoin('users as users2', 'users2.id', '=', 'animals.confirm_user_id');

        $aliases = [
            'species_name' => 'species.name',
            'breeds_name' => 'breeds.name',
            'colors_name' => 'colors.name',
            'owner_name' => 'CONCAT(`users1`.last_name, \' \', `users1`.first_name, \'||\', `users1`.id)',
            'verified_name' => 'CONCAT(`users2`.last_name, \' \', `users2`.first_name, \'||\', `users2`.id)'
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function animalCreate()
    {
        return view('admin.db.animals_create', [
            'species' => Species::get()
        ]);
    }

    public function animalStore(Request $request)
    {
        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'birthday',
            'sterilized', 'comment', 'images', 'documents']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'birthday' => 'required|date',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000',
            'images' => 'required|array',
            'images.*' => 'required|file',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file',
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'animal')
                ->withInput();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);

        $animal = \Auth::user()->animals()->create($data);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        return redirect()
            ->route('admin.db.animals.edit', $animal->id)
            ->with('success_animal', 'Тварину додано успішно !');
    }

    public function animalEdit($id)
    {
        $animal = $this->animalModel
            ->findOrFail($id);

        $animal->load('files');

        return view('admin.db.animals_edit', [
            'animal' => $animal,
            'species' => Species::get()
        ]);
    }

    public function animalUpdate(Request $request, $id)
    {
        $animal = $this->animalModel
            ->findOrFail($id);

        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'birthday',
            'sterilized', 'comment', 'images', 'documents']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'birthday' => 'required|date',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000'
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'comment.max' => 'Коментарій має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'animal')
                ->withInput();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);

        $data['sterilized'] = array_key_exists('sterilized', $data);

        $animal->fill($data);
        $animal->save();

        return redirect()
            ->back()
            ->with('success_animal', 'Данні оновлено успішно !');
    }

    public function animalVerify(Request $request, $id) {
        if ($request->has('state')) {
            $animal = $this->animalModel
                ->findOrFail($id);
            $state = +$request->get('state');
            if ($state === 0 || $state === 1) {
                $animal->verified = $state;
                $animal->confirm_user_id = ($state === 1) ? \Auth::id() : null;
                $animal->save();
                return redirect()->back();
            }
            return response('', 422);
        }
        return response('', 400);
    }

    public function animalUploadFile(Request $request, $id)
    {
        $data = $request->only(['images', 'documents']);

        $validator = Validator::make($data, [
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()
                ->back()
                ->withErrors($validator, 'animal')
                ->withInput();
        }

        $animal = Animal::findOrFail($id);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        return redirect()
            ->route('admin.db.animals.edit', $animal->id)
            ->with('success_animal', 'Файли додано успішно !');
    }

    public function animalRemoveFile($id)
    {
        $file = AnimalsFile::findOrFail($id);
        $file->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }
}
