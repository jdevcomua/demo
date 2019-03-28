<?php

namespace App\Http\Controllers;

use App\Events\AnimalFoundCreated;
use App\Http\Requests\IFoundAnimal;
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

        return view('lost-animals.index', compact('lostAnimals'));  //Todo на вьюхе нет пагинации
    }

    public function foundIndex(FoundAnimal $foundAnimal)
    {
        $foundAnimals = $foundAnimal->sortable(['created_at' => 'desc'])->where(['approved' => 1])->paginate();

        return view('lost-animals.found', compact('foundAnimals'));
    }

    public function iFoundAnimal(IFoundAnimal $request) //Todo: закос под яблочную продукцию?
    {
        $dataToSave = $request->validated();

        $foundAnimal = FoundAnimal::create($dataToSave);

        $this->filesService->handleFoundAnimalFilesUpload($foundAnimal, $dataToSave);

        event(new AnimalFoundCreated($foundAnimal->contact_email));

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
        //Todo ?
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Todo ?
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

    public function lostShow($id)
    {
        $animal = LostAnimal::find($id)->animal;
        $animal->load(['species', 'color', 'files']);
        $animal->imagesArray = $animal->images->pluck('path', 'num')->toArray();

        return view('lost-animals.lost_show', [
            'animal' => $animal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Todo ?
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
        //Todo ?
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Todo ?
    }
}
