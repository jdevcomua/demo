<?php

namespace App\Http\Controllers\Admin;

use App\Events\AnimalAdded;
use App\Helpers\DataTables;
use App\Http\Requests\ArchiveAnimal;
use App\Models\Animal;
use App\Models\AnimalsFile;
use App\Models\CauseOfDeath;
use App\Models\DeathArchiveRecord;
use App\Models\Log;
use App\Models\MovedOutArchiveRecord;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Species;
use App\Models\UserAddress;
use App\Models\UserEmail;
use App\Models\UserPhone;
use App\Rules\Badge;
use App\Rules\Phone;
use App\Services\FilesService;
use App\User;
use Cache;
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
        $animal = Animal::find($id);

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
            ->leftJoin('users as users1', 'users1.id', '=', 'animals.user_id');

        if ($id) $query->where('users1.id', '=', $id);

        $aliases = [
            'species_name' => 'species.name',
            'breeds_name' => 'breeds.name',
            'colors_name' => 'colors.name',
            'owner_name' => 'CONCAT(`users1`.last_name, \' \', `users1`.first_name, \'||\', `users1`.id)',
            'owner_type' => 'if (animals.user_id IS NULL, 0, 1)',
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
            'users' => json_encode($users)
        ]);
    }

    public function animalStore(Request $request)
    {
        $data = $request->only(['user_id', 'nickname', 'species', 'gender', 'breed', 'color', 'fur', 'user',
            'birthday', 'sterilized', 'comment', 'images', 'documents', 'badge']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'user' => 'nullable|integer|exists:users,id',
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
            'images.*' => 'required|image|max:2048',
            'badge' => [
                'nullable',
                'unique:animals,badge',
                new Badge()
            ],
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
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Файли повинні бути в форматі зображення!',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Файли повинні бути в форматі зображення або текстового документу!',
            'badge.unique' => 'Номер жетону вже використовується'
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
            event(new AnimalAdded($user, [$animal]));
        } else {
            $animal = Animal::create($data);
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
            'causesOfDeath' => CauseOfDeath::all()
        ]);
    }

    public function animalUpdate(Request $request, $id)
    {
        $animal = $this->animalModel
            ->findOrFail($id);

        $data = $request->only(['nickname', 'species', 'gender', 'breed', 'color', 'fur', 'user',
            'birthday', 'sterilized', 'comment', 'images', 'documents', 'badge']);

        if (array_key_exists('birthday', $data)) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $validator = Validator::make($data, [
            'user' => 'nullable|integer|exists:users,id',
            'nickname' => 'required|string|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'badge' => [
                'nullable',
                'unique:animals,badge,' . $id,
                new Badge()
            ],
            'comment' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
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
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
            'badge.unique' => 'Номер жетону вже використовується'
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

    public function animalVerify(Request $request, $id) {
        if ($request->has('state')) {
            $animal = $this->animalModel
                ->findOrFail($id);
            $state = +$request->get('state');
            if ($state === 0 || $state === 1) {
                \RhaLogger::start();
                \RhaLogger::update([
                    'action' => Log::ACTION_VERIFY,
                    'user_id' => \Auth::id(),
                ]);
                \RhaLogger::object($animal);
                $oldAnimal = clone $animal;
                $animal->verified = $state;
                $animal->confirm_user_id = ($state === 1) ? \Auth::id() : null;
                $animal->save();
                \RhaLogger::addChanges($animal, $oldAnimal, true, ($animal != null));

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
        $file = AnimalsFile::findOrFail($id);
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

    public function addIdentifyingDevice(Request $request, $id)
    {
        $requestData = $request->all();


        $rulesByTypes = [
            'clip' => 'required',
            'chip' => 'required|between:15,15',
            'badge' => 'required|between:5,8',
        ];


        $rules = [
            'device_type' => 'required'
        ];

        $messages = [
            '*.required' => 'Номер та тип пристрою є обов\'язковим полем!',
            '*.between' => 'Номер данного пристрою повинен складатися мінімум з :min символів та максимум з :max символів!',
        ];

        if(isset($rulesByTypes[$requestData['device_type']])) {
            $rules['device_number'] = $rulesByTypes[$requestData['device_type']];
        }

        $validator = Validator::make($requestData, $rules, $messages);

        $validator->validate();

        $device_column = $requestData['device_type'];

        $animal = Animal::findOrFail($id);
        $animal->$device_column = $requestData['device_number'];
        $animal->save();

        return back()->with('success_identifying_device', 'Пристрій було додано успішно!');
    }

    public function removeIdentifyingDevice(Request $request, $id)
    {
        $requestData = $request->all();
        $device_column = $requestData['device_type'];

        $animal = Animal::findOrFail($id);
        $animal->$device_column = null;
        $animal->save();

        return back()->with('success_identifying_device', 'Пристрій було видалено успішно!');
    }
}
