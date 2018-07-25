<?php

namespace App\Http\Controllers;

use App\Services\FilesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class PetsController extends Controller
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

        return view('pets.index', [
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
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);

        $animal = \Auth::user()->animals()->create($data);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        //TODO redirect to verify your animal note page
        return response()->json([
            'status' => 'ok',
            'url' => route('pets.index')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
