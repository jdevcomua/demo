<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalsFile;
use App\Services\FilesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AnimalsController extends Controller
{

    private $filesService;

    public function __construct(FilesService $filesService)
    {
        $this->filesService = $filesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $pets = $user->animals()->with(['species', 'color', 'images'])->get();

        return view('animals.index', [
            'pets' => $pets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('animals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'fur',
                                'birthday', 'sterilized', 'comment', 'images', 'documents']);

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
            'fur' => 'required|integer|exists:furs,id',
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
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        $data['fur_id'] = $data['fur'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);
        unset($data['fur']);

        $animal = \Auth::user()->animals()->create($data);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        return response()->json([
            'status' => 'ok',
            'url' => route('animals.add-success')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Animal $animal
     * @return void
     */
    public function show(Animal $animal)
    {
        $animal->load(['species', 'color', 'images', 'documents']);
        $animal->images = $animal->images->pluck('path', 'num')->toArray();

        return view('animals.show', [
            'animal' => $animal
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Animal $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        if ($animal->verified) return redirect()->route('animals.show', $animal->id);

        $animal->load(['species', 'color', 'images', 'documents']);
        $animal->images = $animal->images->pluck('path', 'num')->toArray();

        return view('animals.edit', [
            'pet' => $animal
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Animal $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'fur',
            'birthday', 'sterilized', 'comment', 'images', 'documents']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }
//        dd($request, $data, $animal);

        $validator = Validator::make($data, [
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'birthday' => 'required|date',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file',
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'comment.max' => 'Коментарій має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        $data['fur_id'] = $data['fur'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);
        unset($data['fur']);

        $data['sterilized'] = array_key_exists('sterilized', $data);

        $animal->fill($data);
        $animal->save();

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        //TODO redirect to verify your animal note page
        return response()->json([
            'status' => 'ok',
            'url' => route('animals.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        if (!$animal->verified) {
            $animal->delete();
        }
        return redirect()->route('animals.index');
    }

    public function removeFile(AnimalsFile $file)
    {
        $file->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }
}
