<?php

namespace App\Http\Controllers\Admin;

use App\Events\AnimalAdded;
use App\Helpers\DataTables;
use App\Http\Requests\AddIdentifyingDevice;
use App\Http\Requests\ArchiveAnimal;
use App\Http\Requests\SterilizationVaccinationRequest;
use App\Models\Animal;
use App\Models\AnimalChronicle;
use App\Models\AnimalOffense;
use App\Models\AnimalVeterinaryMeasure;
use App\Models\CauseOfDeath;
use App\Models\IdentifyingDevice;
use App\Models\IdentifyingDeviceType;
use App\Models\Log;
use App\Models\Offense;
use App\Models\OffenseAffiliation;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Species;
use App\Models\Sterilization;
use App\Models\UserAddress;
use App\Models\UserEmail;
use App\Models\UserPhone;
use App\Models\Vaccination;
use App\Models\VeterinaryMeasure;
use App\Models\VeterinaryPassport;
use App\Rules\Phone;
use App\Services\Animals\AnimalChronicleServiceInterface;
use App\Services\FilesService;
use App\User;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
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
        if (!\Auth::user()->can('private-data')) {
            $model->makeHidden(['passport', 'inn']);
        }

        $query = $model->newQuery()
            ->leftJoin('user_emails', 'user_emails.user_id', '=', 'users.id')
            ->leftJoin('user_phones', 'user_phones.user_id', '=', 'users.id')
            ->leftJoin('user_addresses', 'user_addresses.user_id', '=', 'users.id')
            ->leftJoin('animals', 'animals.user_id', '=', 'users.id')
            ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            ->groupBy('users.id');

        //COALESCE для того, чтоб не обваливалось при пустых значениях

        $aliases = [
            'emails' => 'GROUP_CONCAT(DISTINCT `user_emails`.email SEPARATOR \'|\')',
            'phones' => 'GROUP_CONCAT(DISTINCT `user_phones`.phone SEPARATOR \'|\')',
            'addresses' => 'GROUP_CONCAT(DISTINCT
                CONCAT(
                    COALESCE(`user_addresses`.city, " "), ", " ,
                    COALESCE(`user_addresses`.street, " "), ", " ,
                    COALESCE(`user_addresses`.building, " "), ", " ,
                    COALESCE(`user_addresses`.apartment, " ")
                       ) SEPARATOR "|")',
            'animals' => 'COUNT(DISTINCT `animals`.id)',
            'organization_name' => 'organizations.name'
        ];
        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function userShow($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        $organizations = Organization::all();

        $user->load('animals', 'roles', 'emails', 'phones', 'addresses');
        return view('admin.db.users_show', compact('user', 'roles', 'organizations'));
    }

    public function userUpdate(Request $request, $id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $data = $request->only(['phone', 'email']);

        $validator = \Validator::make($data,[
            'phone' => [
                'nullable',
                'min:6',
                'max:20',
                new Phone
            ],
            'email' => 'nullable|email'
        ],[
            'phone.min' => 'Номер телефону має бути більше ніж :min символів',
            'phone.max' => 'Номер телефону має бути менше :max символів',
            'email.email' => 'Поштова адреса введена некоректно',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'user')
                ->withInput();
        }

        $phone = $user->phonesAdditional()->first();
        $email = $user->emailsAdditional()->first();

        $phoneData = [
            'phone' => $data['phone'] ?? '',
            'type' => UserPhone::TYPE_MANUAL
        ];
        $emailData = [
            'email' => $data['email'] ?? '',
            'type' => UserEmail::TYPE_MANUAL
        ];

        if ($phone) {
            $phone->update($phoneData);
        } else {
            $user->phones()->create($phoneData);
        }

        if ($email) {
            $email->update($emailData);
        } else {
            $user->emails()->create($emailData);
        }

        return redirect()
            ->back()
            ->with('success_user', 'Дані оновлено успішно!');
    }

    public function userAttachOrganization(Request $request)
    { //Todo убрать дублирование кода
        $user = User::find($request->user_id);
        $organization = Organization::find($request->organization_id);


        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($user);
        $oldUser = clone $user;

        $user->organization()->associate($organization);

        $user->save();
        $user->load('organization');


        \RhaLogger::addChanges($user, $oldUser, true, ($user != null));


        return back()->with('success_user', 'Організація була закріплена успішно!');
    }

    public function userDetachOrganization(Request $request)
    {
        $user = User::find($request->user_id);
        $organization = Organization::find($request->organization_id);


        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($user);
        $oldUser = clone $user;

        $user->organization()->dissociate($organization);

        $user->save();
        $user->load('organization');


        \RhaLogger::addChanges($user, $oldUser, true, ($user != null));


        return back()->with('success_user', 'Організація була відкріплена успішно!');
    }

    public function userUpdateAddress(Request $request, $id)
    {
        $data = $request->only([
            'registrationAddress',
            'livingAddress',
        ]);

        $user = User::findOrFail($id);

        $validator = Validator::make($data, [
            'registrationAddress' => 'required|array',
            'registrationAddress.*' => 'required',
        ], [
            'registrationAddress.required' => 'Адреса реєстрації є обов\'язковою',
            'registrationAddress.district.required' => 'Область є обов\'язковим полем',
            'registrationAddress.city.required' => 'Населений пункт є обов\'язковим полем',
            'registrationAddress.street.required' => 'Вулиця є обов\'язковим полем',
            'registrationAddress.building.required' => 'Будинок є обов\'язковим полем',
            'registrationAddress.apartment.required' => 'Помешкання є обов\'язковим полем',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'user_address')
                ->withInput();
        }

        $registrationAddress = $user->addresses()->where('type', UserAddress::ADDRESS_TYPE_REGISTRATION)->first();

        if (!$registrationAddress) {
            $registrationAddress = new UserAddress();
            $registrationAddress->type = UserAddress::ADDRESS_TYPE_REGISTRATION;
            $registrationAddress->country_code = 'ua';
            $registrationAddress->user_id = $user->id;
        }

        $registrationAddress->district = $data['registrationAddress']['district'];
        $registrationAddress->city = $data['registrationAddress']['city'];
        $registrationAddress->street = $data['registrationAddress']['street'];
        $registrationAddress->building = $data['registrationAddress']['building'];
        $registrationAddress->apartment = $data['registrationAddress']['apartment'];
        $registrationAddress->save();


        if($data['livingAddress']['city']) {

            $livingAddress = $user->addresses()->where('type', UserAddress::ADDRESS_TYPE_LIVING)->first();

            if (!$livingAddress) {
                $livingAddress = new UserAddress();
                $livingAddress->type = UserAddress::ADDRESS_TYPE_LIVING;
                $livingAddress->country_code = 'ua';
                $livingAddress->user_id = $user->id;
            }

            $livingAddress->district = $data['livingAddress']['district'];
            $livingAddress->city = $data['livingAddress']['city'];
            $livingAddress->street = $data['livingAddress']['street'];
            $livingAddress->building = $data['livingAddress']['building'];
            $livingAddress->apartment = $data['livingAddress']['apartment'];
            $livingAddress->save();

        }

        return redirect()
            ->back()
            ->with('success_user_address', 'Адреса була змінена успішно!');
    }

    public function userUpdateRoles(Request $request, $id)
    {
        $data = $request->only('roles');

        $validator = \Validator::make($data, [
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id', // check each item in the array
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'user_roles');
        }

        if (!array_key_exists('roles', $data)) $data['roles'] = array();

        $user = User::findOrFail($id);
        $user->roles()->sync($data['roles']);
        Cache::tags(config('entrust.role_user_table'))->flush();

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
        Cache::tags(config('entrust.role_user_table'))->flush();

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

    public function animalArchiveIndex()
    {
        $causes_of_deaths = CauseOfDeath::all('id', 'name');
        $causes_of_deaths_array = [];

        foreach ($causes_of_deaths as $causes_of_death) {
            $causes_of_deaths_array[$causes_of_death->id] = $causes_of_death->name;
        }


        return view('admin.db.animals_archive', [
            'species' => Species::get(),
            'causes_of_deaths_array' => $causes_of_deaths_array
        ]);
    }

    public function animalArchive(ArchiveAnimal $request, $id)
    {
        $requestData = $request->all();
        $animal = Animal::find($id);

        \RhaLogger::start(['data' => $requestData]);
        \RhaLogger::update([
            'action' => ($requestData['archive_type'] !== 'moved_out') ? Log::ACTION_ANIMAL_DEATH : Log::ACTION_ANIMAL_MOVED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $request->validate($request->rules());

        $request->persist($animal);
        return back();
    }

    public function animalData(Request $request, $id = null)
    {
        $model = new Animal();

        $query = $model->newQuery()
            ->whereNull('archived_type')
            ->join('species', 'species.id', '=', 'animals.species_id')
            ->join('breeds', 'breeds.id', '=', 'animals.breed_id')
            ->join('colors', 'colors.id', '=', 'animals.color_id')
            ->leftJoin('identifying_devices as badge', function ($join) {
                $join->on('badge.animal_id', '=', 'animals.id');
                $join->where('badge.identifying_device_type_id', '=', IdentifyingDeviceType::TYPE_BADGE);
            })
            ->leftJoin('identifying_devices as chip', function ($join) {
                $join->on('chip.animal_id', '=', 'animals.id');
                $join->where('chip.identifying_device_type_id', '=', IdentifyingDeviceType::TYPE_CHIP);
            })
            ->leftJoin('identifying_devices as clip', function ($join) {
                $join->on('clip.animal_id', '=', 'animals.id');
                $join->where('clip.identifying_device_type_id', '=', IdentifyingDeviceType::TYPE_CLIP);
            })
            ->leftJoin('identifying_devices as brand', function ($join) {
                $join->on('brand.animal_id', '=', 'animals.id');
                $join->where('brand.identifying_device_type_id', '=', IdentifyingDeviceType::TYPE_BRAND);
            })
            ->leftJoin('users as users1', 'users1.id', '=', 'animals.user_id')
        ;

        if ($id) $query->where('users1.id', '=', $id);

        $aliases = [
            'species_name' => 'species.name',
            'breeds_name' => 'breeds.name',
            'colors_name' => 'colors.name',
            'owner_name' => 'CONCAT(`users1`.last_name, \' \', `users1`.first_name, \'||\', `users1`.id)',
            'owner_type' => 'if (animals.user_id IS NULL, 0, 1)',
            'badge_number' => 'badge.number',
            'chip_number' => 'chip.number',
            'clip_number' => 'clip.number',
            'brand_number' => 'brand.number',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function animalArchiveData(Request $request, $id = null)
    {
        $model = new Animal();

        $query = $model->newQuery()
            ->where('archived_type', '!=', 'NULL')
            ->leftJoin('death_archive_records', 'death_archive_records.id', '=', 'animals.archived_id')
            ->leftJoin('moved_out_archive_records', 'moved_out_archive_records.id', '=', 'animals.archived_id')
            ->join('species', 'species.id', '=', 'animals.species_id')
            ->join('breeds', 'breeds.id', '=', 'animals.breed_id')
            ->join('colors', 'colors.id', '=', 'animals.color_id')
            ->leftJoin('users as users1', 'users1.id', '=', 'animals.user_id');

        if ($id) $query->where('users1.id', '=', $id);

        $aliases = [
            'species_name' => 'species.name',
            'breeds_name' => 'breeds.name',
            'colors_name' => 'colors.name',
            'owner_name' => 'CONCAT(`users1`.last_name, \' \', `users1`.first_name, \'||\', `users1`.id)',
            'owner_type' => 'if (animals.user_id IS NULL, 0, 1)',
            'death' => 'death_archive_records.cause_of_death_id',
            'death_date' => 'death_archive_records.died_at',
            'moved_out_date' => 'moved_out_archive_records.moved_out_at'

        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }


    public function animalCreate($id = null)
    {
        $animal = new Animal();
        $user = ($id) ? User::findOrFail($id) : null;

        $users = User::all()->pluck('name', 'id')->toArray();

        $users = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $users, array_keys($users));

        return view('admin.db.animals_create', [
            'species' => Species::get(),
            'user' => $user,
            'users' => json_encode($users),
            'animal' => $animal,
            'identifyingDeviceTypes' => IdentifyingDeviceType::all()
        ]);
    }

    public function animalStore(Request $request, AnimalChronicleServiceInterface $chs)
    { //Todo убрать дублирование кода
        $data = $request->only(['user_id', 'nickname', 'nickname_lat', 'species', 'gender', 'breed', 'color', 'fur', 'user',
            'birthday', 'sterilized', 'comment', 'images', 'documents', 'device_type', 'device_number', 'tallness', 'testing']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $deviceType = $data['device_type'];
        $deviceNumber = $data['device_number'];

        switch ($deviceType) {
            case Animal::IDENTIFYING_DEVICES_TYPE_BADGE: $deviceNumberRule = 'nullable|between:5,8'; break;
            case Animal::IDENTIFYING_DEVICES_TYPE_CLIP: $deviceNumberRule = 'nullable'; break;
            case Animal::IDENTIFYING_DEVICES_TYPE_CHIP: $deviceNumberRule = 'nullable|size:15'; break;
            case Animal::IDENTIFYING_DEVICES_TYPE_BRAND: $deviceNumberRule = 'nullable'; break;
        }

        $rules = [
            'user' => 'nullable|integer|exists:users,id',
            'nickname' => 'required|string|max:256',
            'nickname_lat' => 'nullable|regex:/^[a-zA-Z]+$/u|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'testing' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:2000',
            'images' => 'required|array',
            'images.*' => 'required|image|max:2048',
            'clip' => 'nullable|unique:animals',
            'chip' => 'nullable|unique:animals|size:15',
            'badge' => 'nullable|unique:animals|between:5,8',
            'tallness' => 'nullable|integer|min:10|max:100',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
            'device_type' => 'nullable'
        ];

        if (isset($deviceNumberRule)) {
            $rules['device_number'] = $deviceNumberRule;
        }

        $messages = [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'nickname_lat.max' => 'Кличка на латині має бути менше :max символів',
            'nickname_lat.regex' => 'Кличка на латині має містити тільки латинські символи',
            'species.required' => 'Вид є обов\'язковим полем',
            'gender.required' => 'Стать є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'fur.required' => 'Тип шерсті є обов\'язковим полем',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'birthday.before' => 'Дата народження не може бути у майбутньому!',
            'birthday.date' => 'Дата народження повинна бути корректною датою',
            'birthday.after' => 'Тварини стільки не живуть!',
            'testing.max' => 'Тестування тварини має бути менше :max символів',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Файли повинні бути в форматі зображення!',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Файли повинні бути в форматі зображення або текстового документу!',
            'tallness.min' => 'Зріст має бути більше :min см',
            'tallness.max' => 'Зріст має бути менше :max см',
            'device_number.size' => 'Номер пристрою повинен складатися з :size символів!',
            'device_number.between' => 'Номер пристрою повинен бути не менше :min символів та не більше :max символів!'
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($this->filterValidatorErrors($validator), 'animal')
                ->withInput();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        $data['fur_id'] = $data['fur'];
        $data['user_id'] = $data['user'];
        unset($data['user']);
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);
        unset($data['fur']);

        $user = User::find($data['user_id']);

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_CREATE,
            'user_id' => \Auth::id(),
        ]);

        if ($user) {
            $animal = $user->animals()->create($data);
            event(new AnimalAdded($user, $animal, [$animal]));
        } else {
            $animal = Animal::create($data);
        }


        if ($deviceType !== null && $deviceNumber !== null) {
            $animal->identifyingDevices()->create([
                'number' => $deviceNumber,
                'issued_by' => \Auth::user()->full_name,
                'identifying_device_type_id' => $deviceType
            ]);

            $chronicleField = AnimalChronicle::getChronicleFieldByType($deviceType);

            $chs->addAnimalChronicle($animal,$chronicleField . '-added', [$chronicleField => $deviceNumber]);
        }


        $this->filesService->handleAnimalFilesUpload($animal, $data);

        \RhaLogger::addChanges($animal, new Animal(), true, ($animal != null));
        if ($animal) \RhaLogger::object($animal);

        return redirect()
            ->route('admin.db.animals.index', $animal->id)
            ->with('success_animal', 'Тварину додано успішно !');
    }

    private function filterValidatorErrors(\Illuminate\Validation\Validator $data)
    {
        $messagesUniq = [];
        $res = [];
        foreach ($data->messages()->messages() as $k => $v) {
            if (array_search($v, $messagesUniq) !== false) continue;
            $res[$k] = $v;
            $messagesUniq[] = $v;
        }
        return $res;
    }

    public function animalEdit($id)
    {
        $animal = $this->animalModel
            ->findOrFail($id);

        $animal->load('files', 'user', 'history', 'history.user');
        $users = User::all()->pluck('name', 'id')->toArray();

        $users = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $users, array_keys($users));

        return view('admin.db.animals_edit', [
            'animal' => $animal,
            'species' => Species::get(),
            'users' => json_encode($users),
            'causesOfDeath' => CauseOfDeath::all(),
            'veterinaryMeasures' => VeterinaryMeasure::all(),
            'offenses' => Offense::all(),
            'offenseAffiliations' => OffenseAffiliation::all()
        ]);
    }

    public function animalUpdate(Request $request, $id)
    {
        $animal = $this->animalModel
            ->findOrFail($id);

        $data = $request->only(['nickname','nickname_lat', 'species', 'gender', 'breed', 'color', 'fur', 'user',
            'birthday', 'sterilized', 'comment', 'images', 'documents', 'tallness', 'testing']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'user' => 'nullable|integer|exists:users,id',
            'nickname' => 'required|string|max:256',
            'nickname_lat' => ['nullable', 'not_regex:/[А-Яа-яЁё]/u', 'max:256'],
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'testing' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:2000',
            'tallness' => 'nullable|integer|min:10|max:100',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ], [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'nickname_lat.max' => 'Кличка на латині має бути менше :max символів',
            'nickname_lat.not_regex' => 'Кличка на латині має містити тільки латинські символи',
            'species.required' => 'Вид є обов\'язковим полем',
            'gender.required' => 'Стать є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'fur.required' => 'Тип шерсті є обов\'язковим полем',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'birthday.before' => 'Дата народження не може бути у майбутньому!',
            'birthday.date' => 'Дата народження повинна бути корректною датою',
            'birthday.after' => 'Тварини стільки не живуть!',
            'testing.max' => 'Тестування тварини має бути менше :max символів',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
            'tallness.min' => 'Зріст має бути більше :min см',
            'tallness.max' => 'Зріст має бути менше :max см'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($this->filterValidatorErrors($validator), 'animal')
                ->withInput();
        }

        $data['species_id'] = $data['species'];
        $data['breed_id'] = $data['breed'];
        $data['color_id'] = $data['color'];
        $data['fur_id'] = $data['fur'];
        $data['user_id'] = $data['user'];
        unset($data['species']);
        unset($data['breed']);
        unset($data['color']);
        unset($data['fur']);
        unset($data['user']);

        $data['sterilized'] = array_key_exists('sterilized', $data);

        \RhaLogger::start(['data' => $data]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);
        $oldAnimal = clone $animal;
        $animal->fill($data);
        $animal->save();
        \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

        return redirect()
            ->back()
            ->with('success_animal', 'Дані оновлено успішно !');
    }

    public function animalRemove($id)
    {
        $animal = Animal::findOrFail($id);
        \RhaLogger::start();
        \RhaLogger::update([
            'action' => Log::ACTION_DELETE,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);
        $animal->delete();
        \RhaLogger::end(true);
        return redirect()
            ->back()
            ->with('success_animal', 'Тварину було успішно видалено!');
    }

    public function animalVerify(Request $request, AnimalChronicleServiceInterface $animalChronicleService, $id) {
        if ($request->has('state')) {
            $animal = $this->animalModel
                ->findOrFail($id);
            $state = +$request->get('state');
            if ($state === 0 || $state === 1) {
                \RhaLogger::start();
                \RhaLogger::update([
                    'action' => $state ? Log::ACTION_VERIFY : Log::ACTION_VERIFY_CANCEL,
                    'user_id' => \Auth::id(),
                ]);
                \RhaLogger::object($animal);
                $oldAnimal = clone $animal;
                $animal->verified = $state;
                $animal->confirm_user_id = ($state === 1) ? \Auth::id() : null;
                $animal->save();
                \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

                $animalChronicleService->addAnimalChronicle($animal, $state ? 'verification-added' : 'verification-removed');

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
            'images.*' => 'nullable|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ], [
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'animal_files')
                ->withInput();
        }

        $animal = Animal::findOrFail($id);

        $this->filesService->handleAnimalFilesUpload($animal, $data);

        return redirect()
            ->route('admin.db.animals.edit', $animal->id)
            ->with('success_animal_files', 'Файли додано успішно !');
    }

    public function animalRemoveFile($id)
    {
        return $this->removeFileCommonLogic('App\Models\AnimalsFile', $id);
    }

    public function veterinaryMeasureRemoveFile($id)
    {
        return $this->removeFileCommonLogic('App\Models\AnimalVeterinaryMeasureFile', $id);
    }

    public function animalOffenseRemoveFile($id)
    {
        return $this->removeFileCommonLogic('App\Models\AnimalOffenseFile', $id);
    }

    private function removeFileCommonLogic($fileEntityClass, $id)
    {
        $file = $fileEntityClass::findOrFail($id);
        Storage::delete($file->path);
        $file->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function userShowAnimals($id)
    {
        $user = User::findOrFail($id);

        return view('admin.db.user_animals', [
            'species' => Species::get(),
            'user' => $user
        ]);
    }

    public function addIdentifyingDevice(AddIdentifyingDevice $request, AnimalChronicleServiceInterface $chs,  $id)
    {
        $validatedData = $request->validated();

        $chronicleTypesMap = [
            Animal::IDENTIFYING_DEVICES_TYPE_CLIP => 'clip-added',
            Animal::IDENTIFYING_DEVICES_TYPE_CHIP => 'chip-added',
            Animal::IDENTIFYING_DEVICES_TYPE_BADGE => 'badge-added',
            Animal::IDENTIFYING_DEVICES_TYPE_BRAND => 'brand-added',
        ];

        $animal = Animal::findOrFail($id);

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_IDEVICE_ADDED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $oldAnimal = clone $animal;

        $animal->identifyingDevices()->create($validatedData);

        $animal->save();

        \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

        $chs->addAnimalChronicle(
            $animal,$chronicleTypesMap[$validatedData['device_type']],
            [AnimalChronicle::getChronicleFieldByType($validatedData['device_type']) => $validatedData['number']]
        );

        return back()->with('success_identifying_device', 'Пристрій було додано успішно!');
    }

    public function removeIdentifyingDevice(Request $request, AnimalChronicleServiceInterface $chs, $id)
    {
        $requestData = $request->all();


        $chronicleTypesMap = [
            Animal::IDENTIFYING_DEVICES_TYPE_CLIP => 'clip-removed',
            Animal::IDENTIFYING_DEVICES_TYPE_CHIP => 'chip-removed',
            Animal::IDENTIFYING_DEVICES_TYPE_BADGE => 'badge-removed',
            Animal::IDENTIFYING_DEVICES_TYPE_BRAND => 'brand-removed',
        ];

        $animal = Animal::findOrFail($id);
        $device = IdentifyingDevice::findOrFail($requestData['id']);
        $deviceType = $device->type->id;

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_IDEVICE_REMOVED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $oldAnimal = clone $animal;
        $device->delete();
        $animal->save();

        \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

        $chs->addAnimalChronicle($animal, $chronicleTypesMap[$deviceType]);

        return back()->with('success_identifying_device', 'Пристрій було видалено успішно!');
    }

    public function addSterilization(SterilizationVaccinationRequest $request, AnimalChronicleServiceInterface $animalChronicleService, $id)
    {
        $animal = Animal::findOrFail($id);

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_STERILIZATION_ADDED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $sterilization = new Sterilization($request->validated());
        $sterilization->animal()->associate($animal);
        $sterilization->save();

        \RhaLogger::addChanges($sterilization, new Sterilization(), true, ($sterilization != null));

        $animalChronicleService->addAnimalChronicle($animal, 'sterilization-added', [
            'date' => \App\Helpers\Date::getlocalizedDate($sterilization->date),
        ]);

        return back()->with('success_sterilization', 'Стерилізацію додано успішно!');
    }

    public function addVaccination(SterilizationVaccinationRequest $request, AnimalChronicleServiceInterface $animalChronicleService, $id)
    {
        $animal = Animal::findOrFail($id);

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_VACCINATION_ADDED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $vaccination = new Vaccination($request->validated());
        $vaccination->animal()->associate($animal);
        $vaccination->save();

        \RhaLogger::addChanges($vaccination, new Vaccination(), true, ($vaccination != null));

        $animalChronicleService->addAnimalChronicle($animal, 'vaccination-added', [
            'date' => \App\Helpers\Date::getlocalizedDate($vaccination->date),
        ]);

        return back()->with('success_vaccination', 'Щеплення було додано успішно!');
    }

    public function addVeterinaryMeasure(Request $request, AnimalChronicleServiceInterface $animalChronicleService, $id)
    {
        $animal = Animal::findOrFail($id);

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_VET_MEASURE_ADDED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $request_data = $request->all();

        $rules = [
            'date' => 'required|date_format:d/m/Y|before:tomorrow',
            'veterinary_measure' => 'required',
            'made_by' => 'required|string',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ];

        $messages = [
            'date.required' => 'Дата проведення є обов\'язковим полем!',
            'date.before' => 'Дата проведення не може бути у майбутньому!',
            'date.date_format' => 'Дата проведення повинна бути корректною!',
            'made_by.required' => 'Поле \'Ким проведено\' є обов\'язковим! ',
            'made_by.string' => 'Поле \'Ким проведено\' має бути строкою! ',
            'veterinary_measure.required' => 'Захід є обов\'язковим полем!',
            'documents.*.max' => 'Файли повинні бути не більше 2mb!',
            'documents.*.mimes' => 'Файли повинні бути в форматі зображення або текстового документу!'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        $validator->validate();

        $veterinarymeasure = VeterinaryMeasure::findOrFail($request_data['veterinary_measure']);


        $animalVeterinaryMeasure = new AnimalVeterinaryMeasure;
        $animalVeterinaryMeasure->date = Carbon::createFromFormat('d/m/Y', $request_data['date'])->toDateString();
        $animalVeterinaryMeasure->made_by = $request_data['made_by'];
        $animalVeterinaryMeasure->description = $request_data['description'];
        $animalVeterinaryMeasure->animal()->associate($animal);
        $animalVeterinaryMeasure->veterinaryMeasure()->associate($veterinarymeasure);
        $animalVeterinaryMeasure->save();

        if (isset($request_data['documents'])) {
            $this->filesService->handleVeterinaryMeasureFilesUpload($animalVeterinaryMeasure, $request_data);
        }

        \RhaLogger::addChanges($animalVeterinaryMeasure, new AnimalVeterinaryMeasure(), true, ($animalVeterinaryMeasure != null));

        $animalChronicleService->addAnimalChronicle($animal, 'veterinary-measure-added', [
            'veterinary_measure' => $veterinarymeasure->name,
            'date' => \App\Helpers\Date::getlocalizedDate($animalVeterinaryMeasure->date)
        ]);

        return back()->with('success_veterinary_measures', 'Ветеринарний захід успішно додано!');
    }

    public function animalVeterinaryMeasure($id)
    {
        $animal_vet_measure = AnimalVeterinaryMeasure::findOrFail($id);

        return view('admin.db.animal_vet_measure_show', compact('animal_vet_measure'));
    }

    public function addAnimalOffense(Request $request, AnimalChronicleServiceInterface $animalChronicleService, $id)
    {
        $animal = Animal::findOrFail($id);

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_OFFENSE_ADDED,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($animal);

        $request_data = $request->all();

        if (!isset($request_data['bite'])) {
            $request_data['bite'] = 0;
        }


        $rules = [
            'date' => 'required|date_format:d/m/Y|before:tomorrow',
            'protocol_date' => 'required|date_format:d/m/Y|before:tomorrow',
            'protocol_number' => 'required|max:20',
            'offense' => 'required',
            'offense_affiliation' => 'required',
            'made_by' => 'required|string',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ];

        $messages = [
            'date.required' => 'Дата правопорушення є обов\'язковим полем!',
            'date.before' => 'Дата правопорушення не може бути у майбутньому!',
            'date.date_format' => 'Дата правопорушення повинна бути корректною!',
            'protocol_date.required' => 'Дата протоколу є обов\'язковим полем!',
            'protocol_date.before' => 'Дата протоколу не може бути у майбутньому!',
            'protocol_date.date_format' => 'Дата протоколу повинна бути корректною!',
            'protocol_number.required' => 'Номер протоколу є обов\'язковим полем!',
            'protocol_number.max' => 'Номер протоколу повинен містити не більше ніж :max символів!',
            'made_by.required' => 'Поле \'Ким зафіксовано\' є обов\'язковим! ',
            'made_by.string' => 'Поле \'Ким зафіксовано\' має бути строкою! ',
            'offense.required' => 'Вид правопорушення є обов\'язковим полем!',
            'offense_affiliation.required' => 'Належність правопорушення є обов\'язковим полем!',
            'documents.*.max' => 'Файли повинні бути не більше 2mb!',
            'documents.*.mimes' => 'Файли повинні бути в форматі зображення або текстового документу!'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        $validator->validate();

        $offense = Offense::findOrFail($request_data['offense']);
        $offenseAffiliation = OffenseAffiliation::findOrFail($request_data['offense_affiliation']);


        $animalOffense = new AnimalOffense;
        $animalOffense->date = Carbon::createFromFormat('d/m/Y', $request_data['date'])->toDateString();
        $animalOffense->protocol_date = Carbon::createFromFormat('d/m/Y', $request_data['protocol_date'])->toDateString();
        $animalOffense->protocol_number = $request_data['protocol_number'];
        $animalOffense->made_by = $request_data['made_by'];
        $animalOffense->description = $request_data['description'];
        $animalOffense->bite = $request_data['bite'];
        $animalOffense->animal()->associate($animal);
        $animalOffense->offense()->associate($offense);
        $animalOffense->offenseAffiliation()->associate($offenseAffiliation);
        $animalOffense->save();

        if (isset($request_data['documents'])) {
            $this->filesService->handleAnimalOffenseFilesUpload($animalOffense, $request_data);
        }

        \RhaLogger::addChanges($animalOffense, new AnimalOffense(), true, ($animalOffense != null));

        $animalChronicleService->addAnimalChronicle($animal, 'animal-offense-added', [
            'offense_affiliation' => $offenseAffiliation->name,
            'date' => \App\Helpers\Date::getlocalizedDate($animalOffense->date),
            'offense' => $offense->name
        ]);

        return back()->with('success_offense', 'Правопорушення успішно додано!');
    }

    public function animalOffense($id)
    {
        $animalOffense = AnimalOffense::findOrFail($id);

        return view('admin.db.animal_offense_show', compact('animalOffense'));
    }

    public function addVeterinaryPassport(int $id, Request $request)
    {
        $request_data  = $request->all();

        $rules = [
            'number' => 'required|max:256',
            'issued_by' => 'required|max:256'
        ];

        $messages = [
            'number.required' => 'Номер паспорту є обов\'язковим полем',
            'issued_by.required' => 'Ким видано є обов\'язковим полем',
            'number.max' => 'Номер паспорту має містити не більше ніж :max символів',
            'issued_by.max' => 'Ким видано має містити не більше ніж :max символів'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        $validator->validate();

        $animal = Animal::findOrFail($id);

        $veterinaryPassport = new VeterinaryPassport;
        $veterinaryPassport->fill($request_data);
        $veterinaryPassport->save();
        $animal->veterinaryPassport()->associate($veterinaryPassport);
        $animal->save();

        return back()->with('success_veterinary_passport', 'Ветеринарний паспорт успішно додано!');
    }

    public function updateVeterinaryPassport(int $id, Request $request)
    {
        $request_data  = $request->all();

        $rules = [
            'number' => 'required|max:256',
            'issued_by' => 'required|max:256'
        ];

        $messages = [
            'number.required' => 'Номер паспорту є обов\'язковим полем',
            'issued_by.required' => 'Ким видано є обов\'язковим полем',
            'number.max' => 'Номер паспорту має містити не більше ніж :max символів',
            'issued_by.max' => 'Ким видано має містити не більше ніж :max символів'
        ];

        $validator = Validator::make($request_data, $rules, $messages);

        $validator->validate();

        $animal = Animal::findOrFail($id);

        $veterinaryPassport = $animal->veterinaryPassport;
        $veterinaryPassport->fill($request_data);
        $veterinaryPassport->save();

        return back()->with('success_veterinary_passport', 'Ветеринарний паспорт успішно оновлено!');
    }

    public function removeVeterinaryPassport(int $id)
    {
        $animal = Animal::findOrFail($id);
        $animal->veterinaryPassport()->delete();
        return back()->with('success_veterinary_passport', 'Ветеринарний паспорт успішно видалено!');
    }
}
