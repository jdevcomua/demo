<?php

namespace App\Http\Controllers;

use App\Events\AnimalFormRequestSent;
use App\Events\AnimalAdded;
use App\Http\Requests\InformAnimalDeath;
use App\Http\Requests\InformAnimalMovedOut;
use App\Models\Animal;
use App\Models\AnimalsFile;
use App\Models\AnimalsRequest;
use App\Models\CauseOfDeath;
use App\Models\ChangeAnimalOwner;
use App\Models\DeathArchiveRecord;
use App\Models\Log;
use App\Models\MovedOutArchiveRecord;
use App\Services\FilesService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
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

        $pets = $user->animals->load(['species', 'color', 'breed', 'files']);

        $pets = $pets->sortBy('archivable');

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

        if (array_key_exists('birthday', $data) && $data['birthday']) {
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
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000',
            'images' => 'required|array',
            'images.1' => 'required|image',
            'images.*' => 'required|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'species.required' => 'Вид є обов\'язковим полем',
            'gender.required' => 'Стать є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'fur.required' => 'Тип шерсті є обов\'язковим полем',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'birthday.before' => 'Дата народження не може бути у майбутньому!',
            'birthday.date' => 'Дата народження повинна бути корректною датою',
            'birthday.after' => 'Тварини стільки не живуть!',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
            'images.1.required' => 'Додайте головне фото тварини!',
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
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

        $user = \Auth::user();

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_CREATE,
            'user_id' => $user->id,
        ]);
        $animal = $user->animals()->create($data);
        \RhaLogger::addChanges($animal, new Animal(), true, ($animal != null));
        if ($animal) \RhaLogger::object($animal);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        event(new AnimalAdded($user));

        \Session::flash('new-animal', ' ');

        return response()->json([
            'status' => 'ok',
            'url' => route('animals.verify')
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
        $animal->load(['species', 'color', 'files']);
        $animal->imagesArray = $animal->images->pluck('path', 'num')->toArray();
        $causesOfDeath = CauseOfDeath::all();

        return view('animals.show', [
            'animal' => $animal,
            'causesOfDeath' => $causesOfDeath
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

        $animal->load(['species', 'color', 'files']);
        $animal->imagesArray = $animal->images->pluck('path', 'num')->toArray();

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

        $validator = Validator::make($data, [
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'species.required' => 'Вид є обов\'язковим полем',
            'gender.required' => 'Стать є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'fur.required' => 'Тип шерсті є обов\'язковим полем',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'birthday.before' => 'Дата народження не може бути у майбутньому!',
            'birthday.date' => 'Дата народження повинна бути корректною датою',
            'birthday.after' => 'Тварини стільки не живуть!',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
            'images.*.image' => 'Файли повинні бути в форматі зображення!',
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'documents.*.max' => 'Документи повинні бути не більше 2',
            'documents.*.mimes' => 'Файли повинні бути в форматі зображення або текстового документу!'
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

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id()
        ]);
        \RhaLogger::object($animal);
        $oldAnimal = clone $animal;
        $animal->fill($data);
        $animal->save();
        \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        \Session::flash('new-animal', ' ');

        return response()->json([
            'status' => 'ok',
            'url' => route('animals.verify')
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
            \RhaLogger::start();
            \RhaLogger::update([
                'action' => Log::ACTION_DELETE,
                'user_id' => \Auth::id(),
            ]);
            \RhaLogger::object($animal);
            $animal->delete();
            \RhaLogger::end(true);
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

    public function findAnimalRequest(Request $request)
    {
        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'fur',
            'birthday', 'street', 'building', 'apartment']);

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
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'street' => 'nullable|string|max:256',
            'building' => 'nullable|string|max:256',
            'apartment' => 'nullable|string|max:256',
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        $data['fur_id'] = $data['fur'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);
        unset($data['fur']);

        $animalRequest = new AnimalsRequest();
        $animalRequest->user_id = \Auth::id();
        $animalRequest->fill($data);
        $animalRequest->save();

        event(new AnimalFormRequestSent($request->user()));

        return redirect()->back();
    }

    public function search (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'badge' => 'required|min:5|max:8',
        ], [
            'badge.required' => 'Номер жетону є обов\'язковим полем',
            'badge.min' => 'Номер жетону повинен бути не менше :min символів',
            'badge.max' => 'Номер жетону повинен бути не біольше :max символів',
        ]);

        $validator->validate();


        $badge = $request->get('badge');
        $animal = Animal::where('badge', $badge)
//            ->whereNull('user_id')
            ->first();

        if (!$animal) {
            return redirect()
                ->back()
                ->with('error', 'На жаль, тварина не знайдена');
        }

        if (\Auth::user()->can('admin-panel')) {
            return redirect()->route('admin.db.animals.edit', $animal->id);
        }

        return redirect()->route('animals.show', $animal->id);
    }

    /**
     * @param Animal $animal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lost(Animal $animal)
    {
        $lost = $animal->lost;

        if ($lost) {
            $lost->found = !$lost->found;
            $lost->save();
        } else {
            $animal->lost()->create();
        }

        return redirect()->back();
    }

    public function changeOwner(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'passport' => 'required',
            'contact_phone' => 'required'
        ]);

        ChangeAnimalOwner::create($request->all());

        return back()
            ->with('success_request', 'Запит було створено успішно');
    }

    public function informDeath(InformAnimalDeath $request)
    {
        $request->validate($request->rules());

        $requestData = $request->all();
        $animal = Animal::find($requestData['animal_id']);

        $requestData['date'] = Carbon::createFromFormat('d/m/Y', $requestData['date'])->toDateString();

        $animal->archived_at = now();


        $archiveRecord = new DeathArchiveRecord;
        $archiveRecord->died_at = $requestData['date'];
        $archiveRecord->cause_of_death_id = $requestData['cause_of_death'];

        $archiveRecord->save();

        $archiveRecord->archived()->save($animal);

        return route('animals.index');
    }

    public function informMoved(InformAnimalMovedOut $request)
    {
        $request->validate($request->rules());
        $requestData = $request->all();

        $animal = Animal::find($requestData['animal_id']);

        $requestData['date'] = Carbon::createFromFormat('d/m/Y', $requestData['date'])->toDateString();

        $archiveRecord = new MovedOutArchiveRecord;
        $archiveRecord->moved_out_at = $requestData['date'];


        $animal->archived_at = now();

        $archiveRecord->save();

        $archiveRecord->archived()->save($animal);

        return route('animals.index');
    }

}
