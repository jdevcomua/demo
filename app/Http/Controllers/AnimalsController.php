<?php

namespace App\Http\Controllers;

use App\Events\AnimalFormRequestSent;
use App\Events\AnimalAdded;
use App\Http\Requests\AnimalRequest;
use App\Http\Requests\InformAnimalDeath;
use App\Http\Requests\InformAnimalMovedOut;
use App\Models\Animal;
use App\Models\AnimalsFile;
use App\Models\AnimalsRequest;
use App\Models\CauseOfDeath;
use App\Models\ChangeAnimalOwner;
use App\Models\DeathArchiveRecord;
use App\Models\Log;
use App\Models\LostAnimal;
use App\Models\MovedOutArchiveRecord;
use App\Models\VeterinaryPassport;
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
    public function store(AnimalRequest $request)
    {
        $data = $request->validated();

        $user = \Auth::user();

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_CREATE,
            'user_id' => $user->id,
        ]);
        $animal = $user->animals()->create($data);

        $this->handleVeterinaryPassport($animal, $data);
        $this->handleIdentifyingDevice($animal, $data);

        \RhaLogger::addChanges($animal, new Animal(), true, ($animal != null));
        if ($animal) \RhaLogger::object($animal);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        event(new AnimalAdded($user, $animal));

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

        $veterinaryMeasures = $animal->veterinaryMeasures->sortByDesc('created_at')->values();

        return view('animals.show', [
            'animal' => $animal,
            'causesOfDeath' => $causesOfDeath,
            'veterinaryMeasures' => $veterinaryMeasures
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
        $passport = $animal->veterinaryPassport;
        $device = $animal->identifyingDevices()->first();

        return view('animals.edit', [
                'pet' => $animal,
                'passport' => $passport,
                'device' => $device,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Animal $animal
     * @return \Illuminate\Http\Response
     */
    public function update(AnimalRequest $request, Animal $animal)
    {
        $data = $request->validated();

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id()
        ]);
        \RhaLogger::object($animal);
        $oldAnimal = clone $animal;
        $animal->fill($data);
        $animal->save();

        $this->handleVeterinaryPassport($animal, $data);
        $this->handleIdentifyingDevice($animal, $data);

        \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        \Session::flash('new-animal', ' ');

        return response()->json([
            'status' => 'ok',
            'url' => route('animals.verify')
        ]);
    }

    protected function handleVeterinaryPassport($animal, $data)
    {
        if (isset($data['veterinary_issued_by']) && isset($data['veterinary_number'])) {
            $dataToSave = [
                'issued_by' => $data['veterinary_issued_by'],
                'number' => $data['veterinary_number'],
            ];

            if (!$animal->veterinaryPassport) {
                $passport = new VeterinaryPassport();
                $passport->fill($dataToSave)->save();

                $animal->veterinaryPassport()->associate($passport);
                $animal->save();
            } else {
                $animal->veterinaryPassport->update($dataToSave);
            }
        } else {
            $passport = $animal->veterinaryPassport;
            if ($passport !== null) {
                $passport->delete();
            }
        }
    }

    protected function handleIdentifyingDevice($animal, $data)
    {
        if (isset($data['device_type'])
            && isset($data['device_number'])
            && isset($data['device_issued_by'])) {
            $dataToSave = [
                'identifying_device_type_id' => $data['device_type'],
                'number' => $data['device_number'],
                'issued_by' => $data['device_issued_by']
            ];

            if (!count($animal->identifyingDevices)) {
                $animal->identifyingDevices()->create($dataToSave);
            } else {
                $animal->identifyingDevices()->first()->update($dataToSave);
            }
        } else {
            $device = $animal->identifyingDevices()->first();
            if ($device !== null) {
                $device->delete();
            }
        }
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
        $badge = $request->get('badge');
        $animal = Animal::where('badge', $badge)
//            ->whereNull('user_id')
            ->first();

        if ($badge === null || !$animal) {  //Todo лишний запрос в БД
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

        $oldLost = ($lost !== null) ? clone $lost : new LostAnimal();

        if ($lost) {
            $lost->found = !$lost->found;

            // If animal lost again - reset processed
            if (!$lost->found) $lost->processed = false;

            $lost->save();
        } else {
            $animal->lost()->create();
        }

        $animal->load('lost');     //Todo см 15 строк выше
        $lost = $animal->lost;   //Todo см 16 строк выше

        \RhaLogger::start();
        \RhaLogger::update([
            'action' => $lost->found ? Log::ACTION_ANIMAL_FOUND : Log::ACTION_ANIMAL_LOST,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        \RhaLogger::addChanges($lost, $oldLost, true, ($lost != null));


        return redirect()->back();
    }

    public function changeOwner(Request $request)
    {
        $requestData = $request->all();

        \RhaLogger::start(['data' => $requestData]);
        \RhaLogger::update([
            'action' => Log::ACTION_ANIMAL_CHANGE_OWNER,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object(Animal::findOrFail($requestData['animal_id']));

        $request->validate([
            'full_name' => 'required',
            'passport' => 'required',
            'contact_phone' => 'required'
        ]);

        $changeOnwer = ChangeAnimalOwner::create($request->all());

        \RhaLogger::addChanges($changeOnwer, new ChangeAnimalOwner(), true, ($changeOnwer != null));

        return back()
            ->with('success_request', 'Запит було створено успішно');
    }

    public function informDeath(InformAnimalDeath $request)
    {
        $request->validate($request->rules()); //Todo Валидация в кастомных реквестах автоматическая

        $requestData = $request->all();
        $animal = Animal::find($requestData['animal_id']);

        \RhaLogger::start(['data' => $requestData]);
        \RhaLogger::update([
            'action' => Log::ACTION_ANIMAL_DEATH,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $requestData['date'] = Carbon::createFromFormat('d/m/Y', $requestData['date'])->toDateString();

        $animal->archived_at = now();


        $archiveRecord = new DeathArchiveRecord;
        $archiveRecord->died_at = $requestData['date'];
        $archiveRecord->cause_of_death_id = $requestData['cause_of_death'];

        $archiveRecord->save();

        $archiveRecord->archived()->save($animal);

        \RhaLogger::addChanges($archiveRecord, new DeathArchiveRecord(), true, ($archiveRecord != null));


        return route('animals.index');
    }

    public function informMoved(InformAnimalMovedOut $request)
    {
        $request->validate($request->rules()); //Todo Валидация в кастомных реквестах автоматическая
        $requestData = $request->all();

        $animal = Animal::find($requestData['animal_id']);

        \RhaLogger::start(['data' => $requestData]);
        \RhaLogger::update([
            'action' => Log::ACTION_ANIMAL_MOVED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $requestData['date'] = Carbon::createFromFormat('d/m/Y', $requestData['date'])->toDateString();

        $archiveRecord = new MovedOutArchiveRecord;
        $archiveRecord->moved_out_at = $requestData['date'];


        $animal->archived_at = now();

        $archiveRecord->save();

        $archiveRecord->archived()->save($animal);

        \RhaLogger::addChanges($archiveRecord, new MovedOutArchiveRecord(), true, ($archiveRecord != null));

        return route('animals.index');
    }

}
