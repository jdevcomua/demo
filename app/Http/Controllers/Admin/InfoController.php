<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataTables;
use App\Http\Requests\NotificationTemplateRequest;
use App\Models\Block;
use App\Models\Breed;
use App\Models\CauseOfDeath;
use App\Models\Color;
use App\Models\DeathArchiveRecord;
use App\Models\Fur;
use App\Models\Log;
use App\Models\NotificationTemplate;
use App\Models\Offense;
use App\Models\OffenseAffiliation;
use App\Models\Organization;
use App\Models\OrganizationsFile;
use App\Models\Species;
use App\Models\VeterinaryMeasure;
use App\Services\FilesService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Validator;

class InfoController extends Controller
{
    private $breedModel;
    private $colorModel;
    private $furModel;
    private $causeOfDeathModel;
    private $filesService;


    public function __construct(Breed $breedModel, Color $colorModel, Fur $furModel, CauseOfDeath $causeOfDeathModel, FilesService $filesService)
    {
        $this->breedModel = $breedModel;
        $this->colorModel = $colorModel;
        $this->causeOfDeathModel = $causeOfDeathModel;
        $this->filesService = $filesService;
    }

    public function directoryIndex()
    {
        return view('admin.info.directory', [
            'species' => Species::get()
        ]);
    }

    public function directoryDataBreed(Request $request)
    {
        $model = new Breed();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'breeds.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreBreed(Request $request)
    {
        if($request->has(['b_species', 'b_name', 'b_fci'])) {
            $data = $request->only(['b_species', 'b_name', 'b_fci']);

            $validator = Validator::make($data, [
                'b_species' => 'required|integer|exists:species,id',
                'b_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('breeds', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('b_name'))
                            ->where('species_id', $request->get('b_species'));
                    })
                ],
                'b_fci' => 'nullable|integer|max:999',
            ], [
                'b_name.required' => 'Назва є обов\'язковим полем',
                'b_name.unique' => 'Назва має бути унікальною',
                'b_name.max' => 'Назва має бути менше :max символів',
                'b_fci.integer' => 'FCI повинен бути числом',
                'b_fci.max' => 'FCI повинен бути менше 1000',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'breed')
                    ->withInput();
            }

            if (!array_key_exists('b_fci', $data) || !$data['b_fci']) $data['b_fci'] = 0;

            $breed = new Breed();
            $breed->species_id = $data['b_species'];
            $breed->name = $data['b_name'];
            $breed->fci = $data['b_fci'];
            $breed->save();

            return redirect()
                ->back()
                ->with('success_breed', 'Порода додана успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateBreed(Request $request)
    {
        $breed = Breed::findOrFail($request->get('id'));

        $validator = Validator::make($request->all(), [
            'species_id' => 'required|integer|exists:species,id',
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('breeds')->where(function ($query) use($breed, $request) {
                    return $query->where('name', $request->get('name'))
                        ->where('species_id', $request->get('species_id'))
                        ->where('id','!=', $breed->id);
                })
            ],
            'fci' => 'nullable|integer|max:999',
        ], [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.unique' => 'Назва має бути унікальною',
            'name.max' => 'Назва має бути менше :max символів',
            'fci.integer' => 'FCI повинен бути числом',
            'fci.max' => 'FCI повинен бути менше 1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'breed_rem')
                ->withInput();
        }

        $breed->species_id = $request->get('species_id');
        $breed->name = $request->get('name');
        $breed->fci = $request->get('fci');
        $breed->save();

        return redirect()
            ->back()
            ->with('success_breed_rem', 'Порода змінена успішно!');
    }

    public function directoryRemoveBreed(Request $request)
    {
        if ($request->has('id')) {
            $breed = $this->breedModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $breed->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити породу. Кількість тварин що її мають: ' . $count,
                    ], 'breed_rem');
            }

            $breed->delete();

            return redirect()
                ->back()
                ->with('success_breed_rem', 'Порода видалена успішно !');
        }
        return response('', 400);
    }

    public function directoryDataColor(Request $request)
    {
        $model = new Color();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'colors.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreColor(Request $request)
    {
        if($request->has(['c_species', 'c_name'])) {
            $data = $request->only(['c_species', 'c_name']);

            $validator = Validator::make($data, [
                'c_species' => 'required|integer|exists:species,id',
                'c_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('colors', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('c_name'))
                            ->where('species_id', $request->get('c_species'));
                    })
                ],
            ], [
                'c_name.required' => 'Назва є обов\'язковим полем',
                'c_name.unique' => 'Назва має бути унікальною',
                'c_name.max' => 'Назва має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'color')
                    ->withInput();
            }

            $color = new Color();
            $color->species_id = $data['c_species'];
            $color->name = $data['c_name'];
            $color->save();

            return redirect()
                ->back()
                ->with('success_color', 'Масть додана успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateColor(Request $request)
    {
        $color = Color::findOrFail($request->get('id'));

        $validator = Validator::make($request->all(), [
            'species_id' => 'required|integer|exists:species,id',
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('colors')->where(function ($query) use($color, $request) {
                    return $query->where('name', $request->get('name'))
                        ->where('species_id', $request->get('species_id'))
                        ->where('id','!=', $color->id);
                })
            ],
        ], [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.unique' => 'Назва має бути унікальною',
            'name.max' => 'Назва має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'color_rem')
                ->withInput();
        }

        $color->species_id = $request->get('species_id');
        $color->name = $request->get('name');
        $color->save();

        return redirect()
            ->back()
            ->with('success_color_rem', 'Масть змінена успішно!');
    }

    public function directoryRemoveColor(Request $request)
    {
        if ($request->has('id')) {
            $color = $this->colorModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $color->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити масть. Кількість тварин що її мають: ' . $count,
                    ], 'color_rem');
            }

            $color->delete();

            return redirect()
                ->back()
                ->with('success_color_rem', 'Масть видалена успішно !');
        }
        return response('', 400);
    }

    public function directoryDataFur(Request $request)
    {
        $model = new Fur();

        $query = $model->newQuery()
            ->join('species', 'species.id', '=', 'furs.species_id');

        $aliases = [
            'species_name' => 'species.name',
        ];

        $response = DataTables::provide($request, $model, $query, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreFur(Request $request)
    {
        if($request->has(['f_species', 'f_name'])) {
            $data = $request->only(['f_species', 'f_name']);

            $validator = Validator::make($data, [
                'f_species' => 'required|integer|exists:species,id',
                'f_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('furs', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('f_name'))
                            ->where('species_id', $request->get('f_species'));
                    })
                ],
            ], [
                'f_name.required' => 'Тип шерсті є обов\'язковим полем',
                'f_name.unique' => 'Тип шерсті має бути унікальним',
                'f_name.max' => 'Тип шерсті має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'fur')
                    ->withInput();
            }

            $color = new Fur();
            $color->species_id = $data['f_species'];
            $color->name = $data['f_name'];
            $color->save();

            return redirect()
                ->back()
                ->with('success_fur', 'Тип шерсті додано успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateFur(Request $request)
    {
        $fur = Fur::findOrFail($request->get('id'));
        $validator = Validator::make($request->all(), [
            'species_id' => 'required|integer|exists:species,id',
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('furs')->where(function ($query) use($fur, $request) {
                    return $query->where('name', $request->get('name'))
                        ->where('species_id', $request->get('species_id'))
                        ->where('id','!=', $fur->id);
                })
            ],
        ], [
            'name.required' => 'Тип шерсті є обов\'язковим полем',
            'name.unique' => 'Тип шерсті має бути унікальним',
            'name.max' => 'Тип шерсті має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'fur_rem')
                ->withInput();
        }
        $fur->species_id = $request->get('species_id');
        $fur->name = $request->get('name');
        $fur->save();

        return redirect()
            ->back()
            ->with('success_fur_rem', 'Тип шерсті змінено успішно !');
    }

    public function directoryRemoveFur(Request $request)
    {
        if ($request->has('id')) {
            $fur = $this->furModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();
            $count = $fur->animals()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити тип шерсті. Кількість тварин що її мають: ' . $count,
                    ], 'fur_rem');
            }

            $fur->delete();

            return redirect()
                ->back()
                ->with('success_fur_rem', 'Тип шерсті видалено успішно !');
        }
        return response('', 400);
    }

    public function directoryDataCauseOfDeath(Request $request)
    {
        $model = new CauseOfDeath;

        $query = $model->newQuery();


        $response = DataTables::provide($request, $model, $query);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreCauseOfDeath(Request $request)
    {
        if($request->has(['d_name'])) {
            $data = $request->only(['d_name']);

            $validator = Validator::make($data, [
                'd_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('cause_of_deaths', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('d_name'));
                    })
                ],
            ], [
                'd_name.required' => 'Причина смерті є обов\'язковим полем',
                'd_name.unique' => 'Причина смерті має бути унікальним',
                'd_name.max' => 'Причина смерті має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'cause_of_deaths')
                    ->withInput();
            }

            $color = new CauseOfDeath;
            $color->name = $data['d_name'];
            $color->save();

            return redirect()
                ->back()
                ->with('success_cause_of_deaths', 'Причину смерті додано успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateCauseOfDeath(Request $request)
    {
        $causeOfDeath = CauseOfDeath::findOrFail($request->get('id'));
        $validator = Validator::make($request->all(), [
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('cause_of_deaths')->where(function ($query) use($causeOfDeath, $request) {
                    return $query->where('name', $request->get('name'));
                })
            ],
        ], [
            'name.required' => 'Причина смерті є обов\'язковим полем',
            'name.unique' => 'Причина смерті має бути унікальним',
            'name.max' => 'Причина смерті має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'cause_of_deaths_rem')
                ->withInput();
        }
        $causeOfDeath->name = $request->get('name');
        $causeOfDeath->save();

        return redirect()
            ->back()
            ->with('success_cause_of_deaths_rem', 'Причину смерті змінено успішно !');
    }

    public function directoryRemoveCauseOfDeath(Request $request)
    {
        if ($request->has('id')) {
            $causeOfDeath = $this->causeOfDeathModel
                ->where('id', '=', $request->get('id'))
                ->firstOrFail();

            $count = DeathArchiveRecord::where('cause_of_death_id', '=', $causeOfDeath->id)->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити причину смерті. Кількість тварин що її мають: ' . $count,
                    ], 'cause_of_deaths_rem');
            }

            $causeOfDeath->delete();

            return redirect()
                ->back()
                ->with('success_cause_of_deaths_rem', 'Причину смерті видалено успішно !');
        }
        return response('', 400);
    }

    public function directoryDataVeterinary(Request $request)
    {
        $model = new VeterinaryMeasure;

        $query = $model->newQuery();

        $response = DataTables::provide($request, $model, $query);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreVeterinary(Request $request)
    {
        if($request->has(['v_name'])) {
            $data = $request->only(['v_name']);

            $validator = Validator::make($data, [
                'v_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('veterinary_measures', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('v_name'));
                    })
                ],
            ], [
                'v_name.required' => 'Ветеринарний захід є обов\'язковим полем',
                'v_name.unique' => 'Ветеринарний захід має бути унікальним',
                'v_name.max' => 'Ветеринарний захід має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'veterinary')
                    ->withErrors($validator, 'veterinary')
                    ->withInput();
            }

            $veterinary = new VeterinaryMeasure;
            $veterinary->name = $data['v_name'];
            $veterinary->save();

            return redirect()
                ->back()
                ->with('success_veterinary', 'Ветеринарний захід додано успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateVeterinary(Request $request)
    {
        $veterinary = VeterinaryMeasure::findOrFail($request->get('id'));
        $validator = Validator::make($request->all(), [
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('veterinary_measures')->where(function ($query) use($veterinary, $request) {
                    return $query->where('name', $request->get('name'));
                })
            ],
        ], [
            'name.required' => 'Причина смерті є обов\'язковим полем',
            'name.unique' => 'Причина смерті має бути унікальним',
            'name.max' => 'Причина смерті має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'cause_of_deaths_rem')
                ->withInput();
        }
        $veterinary->name = $request->get('name');
        $veterinary->save();

        return redirect()
            ->back()
            ->with('success_veterinary_rem', 'Ветеринарний захід змінено успішно !');
    }

    public function directoryRemoveVeterinary(Request $request)
    {
        if ($request->has('id')) {
            $veterinary = VeterinaryMeasure::findOrFail($request->get('id'));

            $count = $veterinary->animalVeterinaryMeasures()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити ветеринарний захід. Кількість тварин що її мають: ' . $count,
                    ], 'veterinary_rem');
            }

            $veterinary->delete();

            return redirect()
                ->back()
                ->with('success_veterinary_rem', 'Ветеринарний захід видалено успішно !');
        }
        return response('', 400);
    }

    public function directoryDataOrganization(Request $request)
    {
        $model = new Organization;

        $query = $model->newQuery();


        $response = DataTables::provide($request, $model, $query);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryEditOrganization(Organization $organization)
    {
        $inputsDisabled = \Auth::user()->can(['edit-organizations']) ? '' : 'disabled';

        return view('admin.info.organization_edit', compact('organization', 'inputsDisabled'));
    }

    public function directoryUpdateOrganization(Request $request, Organization $organization)
    {
        $rules = [
            'name' => 'required|string|max:256',
            'chief_full_name' => 'required|string|max:256',
            'contact_info' => 'required|string',
            'address' => 'required|string',
            'requisites' => 'required|string',
        ];

        $messages = [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.max' => 'Назва має бути менше :max символів',
            'chief_full_name.required' => 'ПІБ представника є обов\'язковим полем',
            'chief_full_name.max' => 'ПІБ представника має бути менше :max символів',
            'address.required' => 'Адреса є обов\'язковим полем',
            'requisites.required' => 'Реквізити є обов\'язковим полем',
            'contact_info.required' => 'Контактні дані є обов\'язковим полем',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($this->filterValidatorErrors($validator), 'organization')
                ->withInput();
        }

        \RhaLogger::start(['data' => $request->all()]);
        \RhaLogger::update([
            'action' => Log::ACTION_EDIT,
            'user_id' => \Auth::id(),
        ]);
        \RhaLogger::object($organization);
        $oldOrganization = clone $organization;
        $organization->fill($request->all());
        $organization->save();
        \RhaLogger::addChanges($organization, $oldOrganization, true, ($organization != null));



        return back()->with('success_organization', 'Організацію оновлено успішно!');
    }

    public function directoryCreateOrganization()
    {
        return view('admin.info.organization_create');
    }

    public function directoryStoreOrganization(Request $request)
    {
        $request_data = $request->except('documents');
        $documents = $request->only('documents');

        $rules = [
            'name' => 'required|string|max:256',
            'chief_full_name' => 'required|string|max:256',
            'contact_info' => 'required|string',
            'address' => 'required|string',
            'requisites' => 'required|string',
        ];

        $messages = [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.max' => 'Назва має бути менше :max символів',
            'chief_full_name.required' => 'ПІБ представника є обов\'язковим полем',
            'chief_full_name.max' => 'ПІБ представника має бути менше :max символів',
            'address.required' => 'Адреса є обов\'язковим полем',
            'requisites.required' => 'Реквізити є обов\'язковим полем',
            'contact_info.required' => 'Контактні дані є обов\'язковим полем',
        ];

        if ($documents) {
            $rules['documents.*'] = 'file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048';
            $messages['documents.*.max'] = 'Документи повинні бути не більше 2Mb';
            $messages['documents.*.mimes'] = 'Документи повинні бути в форматі зображення або текстового документу!';
        }

        $validator = Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($this->filterValidatorErrors($validator), 'organization')
                ->withInput();
        }

        \RhaLogger::start(['data' => $request_data]);
        \RhaLogger::update([
            'action' => Log::ACTION_CREATE,
            'user_id' => \Auth::id(),
        ]);

        $organization = Organization::create($request_data);

        if ($documents) {
            $this->filesService->handleOrganizationFilesUpload($organization, $documents);
        }

        \RhaLogger::addChanges($organization, new Organization(), true, ($organization != null));
        if ($organization) \RhaLogger::object($organization);

        return redirect()->route('admin.info.directories.index')->with('success', 'Організацію успешно створено!');
    }

    public function directoryRemoveOrganization(Request $request)
    {
        if ($request->has('id')) {
            $organization = Organization::find($request->id);

            if ($organization->user) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити організацію так як вона закріплена за користувачем.',
                    ], 'organization_rem');
            }

            \RhaLogger::start();
            \RhaLogger::update([
                'action' => Log::ACTION_DELETE,
                'user_id' => \Auth::id(),
            ]);
            \RhaLogger::object($organization);
            $organization->delete();
            \RhaLogger::end(true);

            return redirect()
                ->back()
                ->with('success_organization_rem', 'Організацію видалено успішно !');
        }
        return response('', 400);
    }

    public function organizationUploadFile(Request $request, $id)
    {
        $data = $request->only(['documents']);

        $validator = Validator::make($data, [
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ], [
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'organization_files')
                ->withInput();
        }

        $organization = Organization::findOrFail($id);

        $this->filesService->handleOrganizationFilesUpload($organization, $data);

        return back()
            ->with('success_organization_files', 'Файли додано успішно !');
    }

    public function organizationRemoveFile($id)
    {
        $file = OrganizationsFile::findOrFail($id);
        $file->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function directoryDataOffenseAffiliation(Request $request)
    {
        $model = new OffenseAffiliation;

        $query = $model->newQuery();


        $response = DataTables::provide($request, $model, $query);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreOffenseAffiliation(Request $request)
    {
        if($request->has(['of_a_name'])) {
            $data = $request->only(['of_a_name']);

            $validator = Validator::make($data, [
                'of_a_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('offense_affiliations', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('of_a_name'));
                    })
                ],
            ], [
                'of_a_name.required' => 'Належність правопорушення є обов\'язковим полем',
                'of_a_name.unique' => 'Належність правопорушення має бути унікальним',
                'of_a_name.max' => 'Належність правопорушення має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'offense_affiliation')
                    ->withErrors($validator, 'offense_affiliation')
                    ->withInput();
            }

            $offenseAffiliation = new OffenseAffiliation;
            $offenseAffiliation->name = $data['of_a_name'];
            $offenseAffiliation->save();

            return redirect()
                ->back()
                ->with('success_offense_affiliation', 'Належність правопорушення додано успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateOffenseAffiliation(Request $request)
    {
        $offenseAffiliation = OffenseAffiliation::findOrFail($request->get('id'));
        $validator = Validator::make($request->all(), [
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('offense_affiliations')->where(function ($query) use($offenseAffiliation, $request) {
                    return $query->where('name', $request->get('name'));
                })
            ],
        ], [
            'name.required' => 'Належність правопорушення є обов\'язковим полем',
            'name.unique' => 'Належність правопорушення має бути унікальним',
            'name.max' => 'Належність правопорушення має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'offense_affiliation_rem')
                ->withInput();
        }
        $offenseAffiliation->name = $request->get('name');
        $offenseAffiliation->save();

        return redirect()
            ->back()
            ->with('success_offense_affiliation_rem', 'Належність правопорушення змінено успішно !');
    }

    public function directoryRemoveOffenseAffiliation(Request $request)
    {
        if ($request->has('id')) {
            $offenseAffiliation = OffenseAffiliation::findOrFail($request->get('id'));

            $count = $offenseAffiliation->animalOffenses()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити належність правопорушення. Кількість тварин що її мають: ' . $count,
                    ], 'offense_affiliation_rem');
            }

            $offenseAffiliation->delete();

            return redirect()
                ->back()
                ->with('success_offense_affiliation_rem', 'Належність правопорушення видалено успішно !');
        }
        return response('', 400);
    }

    public function directoryDataOffense(Request $request)
    {
        $model = new Offense;

        $query = $model->newQuery();


        $response = DataTables::provide($request, $model, $query);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function directoryStoreOffense(Request $request)
    {
        if($request->has(['of_name'])) {
            $data = $request->only(['of_name']);

            $validator = Validator::make($data, [
                'of_name' => ['required',
                    'string',
                    'max:256',
                    Rule::unique('offenses', 'name')->where(function ($query) use($request) {
                        return $query->where('name', $request->get('of_name'));
                    })
                ],
            ], [
                'of_name.required' => 'Вид правопорушення є обов\'язковим полем',
                'of_name.unique' => 'Вид правопорушення має бути унікальним',
                'of_name.max' => 'Вид правопорушення має бути менше :max символів',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator, 'offense')
                    ->withErrors($validator, 'offense')
                    ->withInput();
            }

            $offense = new Offense;
            $offense->name = $data['of_name'];
            $offense->save();

            return redirect()
                ->back()
                ->with('success_offense', 'Вид правопорушення додано успішно !');
        }
        return response('', 400);
    }

    public function directoryUpdateOffense(Request $request)
    {
        $offense = Offense::findOrFail($request->get('id'));
        $validator = Validator::make($request->all(), [
            'name' => ['required',
                'string',
                'max:256',
                Rule::unique('offenses')->where(function ($query) use($offense, $request) {
                    return $query->where('name', $request->get('name'));
                })
            ],
        ], [
            'name.required' => 'Вид правопорушення є обов\'язковим полем',
            'name.unique' => 'Вид правопорушення має бути унікальним',
            'name.max' => 'Вид правопорушення має бути менше :max символів',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'offense_rem')
                ->withInput();
        }
        $offense->name = $request->get('name');
        $offense->save();

        return redirect()
            ->back()
            ->with('success_offense_rem', 'Вид правопорушення змінено успішно !');
    }

    public function directoryRemoveOffense(Request $request)
    {
        if ($request->has('id')) {
            $offense = Offense::findOrFail($request->get('id'));

            $count = $offense->animalOffenses()->count();
            if ($count) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'err' => 'Неможливо видалити вид правопорушення. Кількість тварин що її мають: ' . $count,
                    ], 'veterinary_rem');
            }

            $offense->delete();

            return redirect()
                ->back()
                ->with('success_offense_rem', 'Вид правопорушення видалено успішно !');
        }
        return response('', 400);
    }


    public function notificationsIndex()
    {
        return view('admin.info.notifications');
    }

    public function notificationsData(Request $request)
    {
        $model = new NotificationTemplate();
        $model->makeHidden(['body', 'events']);

        $aliases = [
            'events' => 'IFNULL(LENGTH(events) - LENGTH(REPLACE(events, \'@\', \'\')) + 1, 0)',
        ];

        $response = DataTables::provide($request, $model, null, $aliases);

        if ($response) return response()->json($response);

        return response('', 400);
    }

    public function notificationsCreate()
    {
        return view('admin.info.notifications_create');
    }

    public function notificationsStore(NotificationTemplateRequest $request, NotificationTemplate $model)
    {
        $data = $request->validated();

        $model = $model->create($data);

        return redirect()
            ->route('admin.info.notifications.edit', ['id' => $model->id])
            ->with('success_notification', 'Нотифікацію було успішно створено!');
    }

    public function notificationsEdit($id)
    {
        $notification = NotificationTemplate::findOrFail($id);
        return view('admin.info.notifications_edit', [
            'notification' => $notification
        ]);
    }

    public function notificationsUpdate(NotificationTemplateRequest $request, $id)
    {
        $data = $request->validated();

        $template = NotificationTemplate::findOrFail($id);

        $template->update($data);

        return redirect()
            ->back()
            ->with('success_notification', 'Нотифікацію було успішно змінено!');
    }

    public function notificationsDestroy($id)
    {
        $template = NotificationTemplate::findOrFail($id);

        if (array_key_exists($template->type, NotificationTemplate::getTypes(false)) === false) {
            return redirect()->back();
        }

        $template->delete();

        return redirect()
            ->route('admin.info.notifications.index')
            ->with('success_notifications', 'Нотифікацію було успішно видалено!');
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

}
