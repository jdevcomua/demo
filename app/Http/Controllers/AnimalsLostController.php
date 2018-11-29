<?php

namespace App\Http\Controllers;

use App\Models\FoundAnimal;
use App\Models\LostAnimal;
use App\Services\FilesService;
use Illuminate\Http\Request;
use Validator;

class AnimalsLostController extends Controller
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
    public function index(LostAnimal $lostAnimal)
    {
        $lostAnimals = $lostAnimal->sortable(['created_at' => 'desc'])->where(['found' => 0])->paginate();

        return view('lost-animals.index', compact('lostAnimals'));
    }

    public function foundIndex(FoundAnimal $foundAnimal)
    {
        $foundAnimals = $foundAnimal->sortable(['created_at' => 'desc'])->paginate();

        return view('lost-animals.found', compact('foundAnimals'));
    }

    public function iFoundAnimal(Request $request)
    {
        $requestData = $request->all();

        $rules = [
            'species' => 'required|integer|exists:species,id',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'found_address' => 'required|string|max:2000',
            'contact_name' => 'required|string|max:2000',
            'contact_phone' => 'required|string|max:2000',
            'contact_email' => 'required|email|string|max:2000',
            'badge' => 'nullable|min:5|max:8',
            'documents' => 'nullable|array|max:2',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png|max:2048',
        ];

        $messages = [
            'species.required' => 'Вид є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'found_address.required' => 'Адреса де знайшли тварину є обов\'язковим полем',
            'contact_name.required' => 'Ваше ім\'я є обов\'язковим полем',
            'contact_phone.required' => 'Ваш телефон є обов\'язковим полем',
            'contact_email.email' => 'Ваш email повинен бути валідним',
            'contact_email.required' => 'Ваш email є обов\'язковим полем',
            'badge.min' => 'Номер жетону повинен складатися мінімум з 5 символів',
            'badge.max' => 'Номер жетону повинен складатися максимум з 8 символів',
            'documents.max' => 'Максимальна кількість фотографій не повинна перевищувати 9 файлів',
            'documents.*.max' => 'Фото повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png',
        ];

        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $dataToSave = [
            'species_id' => $requestData['species'],
            'breed_id' => $requestData['breed'],
            'color_id' => $requestData['color'],
            'found_address' => $requestData['found_address'],
            'contact_name' => $requestData['contact_name'],
            'contact_phone' => $requestData['contact_phone'],
            'contact_email' => $requestData['contact_email']
        ];

        if(isset($requestData['badge'])) {
            $dataToSave['badge'] = $requestData['badge'];
        }

        $foundAnimal = FoundAnimal::create($dataToSave);

        $this->filesService->handleFoundAnimalFilesUpload($foundAnimal, $requestData);



        return response()->json([
            'status' => 'ok',
            'url' => route('lost-animals.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animal = FoundAnimal::findOrFail($id);
        $animal->load(['species', 'color', 'images']);
        $animal->imagesArray = $animal->images->pluck('path')->toArray();


        return view('lost-animals.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
