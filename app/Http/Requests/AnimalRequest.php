<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'nickname' => 'required|string|max:256',
            'nickname_lat' => 'nullable|not_regex:/[А-Яа-яЁё]/u|max:256',
            'species' => 'required|integer|exists:species,id',
            'gender' => 'required|integer|in:0,1',
            'breed' => 'required|integer|exists:breeds,id',
            'color' => 'required|integer|exists:colors,id',
            'fur' => 'required|integer|exists:furs,id',
            'tallness' => 'nullable|integer|min:10|max:100',
            'birthday' => 'required|date|after:1940-01-01|before:tomorrow',
            'sterilized' => 'nullable|in:1',
            'half_breed' => 'nullable|in:1',
            'comment' => 'nullable|string|max:2000',
            'agree' => 'required|in:1',
            'veterinary_number' => 'nullable|max:255',
            'veterinary_issued_by' => 'nullable|string|max:255',
            'device_type' => 'nullable|exists:identifying_device_types,id',
            'device_number' => [
                'nullable',
                'max:255',
                Rule::unique('identifying_devices','number')->where(function ($query) {
                    return $query->where('identifying_device_type_id', $this->get('device_type'));
                })
            ],
            'device_issued_by' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,txt,doc,docx,xls,xlsx,pdf|max:2048',
        ];

        $imagesRules = [
            'images' => 'required|array',
            'images.1' => 'required|image',
            'images.*' => 'required|image|max:2048',
        ];

        $data = $this->all();

        if (isset($data['editing'])) {
            $imagesRules = [
                'images' => 'nullable|array',
                'images.*' => 'nullable|image|max:2048',
            ];
        }

        $this->makeRequiredIfOneIsset($data,$rules, 'veterinary_number', 'veterinary_issued_by');
        $this->makeRequiredIfOneIsset($data,$rules, 'device_type', 'device_number', 'device_issued_by');

        return array_merge($rules, $imagesRules);
    }

    protected function makeRequiredIfOneIsset($data, &$rules, ...$inputNames)
    {
        $atLeastOneIsset = false;

        foreach ($inputNames as $inputName) {
            if (isset($data[$inputName])) {
                $atLeastOneIsset = true;
                break;
            }
        }

        if ($atLeastOneIsset) {
            foreach ($inputNames as $inputName) {
                $rules[$inputName] = str_replace('nullable', 'required', $rules[$inputName]);
            }
        }
    }

    public function messages()
    {
        return [
            'nickname.required' => 'Кличка є обов\'язковим полем',
            'nickname.max' => 'Кличка має бути менше :max символів',
            'nickname_lat.max' => 'Кличка на латині має бути менше :max символів',
            'nickname_lat.not_regex' => 'Кличка на латині має містити тільки латинські символи',
            'species.required' => 'Вид є обов\'язковим полем',
            'gender.required' => 'Стать є обов\'язковим полем',
            'breed.required' => 'Порода є обов\'язковим полем',
            'color.required' => 'Масть є обов\'язковим полем',
            'fur.required' => 'Тип шерсті є обов\'язковим полем',
            'tallness.min' => 'Зріст має бути більше :min см',
            'tallness.max' => 'Зріст має бути менше :max см',
            'tallness.integer' => 'Зріст має бути числом',
            'birthday.required' => 'Дата народження є обов\'язковим полем',
            'birthday.before' => 'Дата народження не може бути у майбутньому!',
            'birthday.date' => 'Дата народження повинна бути корректною датою',
            'birthday.after' => 'Тварини стільки не живуть!',
            'comment.max' => 'Коментарій має бути менше :max символів',
            'agree.required' => 'Ви не можете додати тварину, якщо не згодні з правилами',
            'images.required' => 'Додайте щонайменше 1 фото вашої тваринки',
            'images.1.required' => 'Додайте головне фото тварини!',
            'images.*.max' => 'Фото повинні бути не більше 2Mb',
            'images.*.image' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg',
            'documents.*.max' => 'Документи повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf',
            'device_type.exists' => 'Виберіть із списку один з типів',
            'device_type.required' => 'Тип засобу є обов\'язковим полем',
            'device_number.required' => 'Номер засобу є обов\'язковим полем',
            'device_number.max' => 'Номер засобу має бути менше :max символів',
            'device_number.unique' => 'Засіб з таким номером уже існує',
            'device_issued_by.max' => 'Ким видано має бути менше :max символів',
            'device_issued_by.required' => 'Ким видано є обов\'язковим полем',
            'veterinary_number.required' => 'Номер ветеринарного паспорту є обов\'язковим полем',
            'veterinary_number.max' => 'Номер ветеринарного паспорту має бути менше :max символів',
            'veterinary_issued_by.max' => 'Ким видано має бути менше :max символів',
            'veterinary_issued_by.required' => 'Ким видано є обов\'язковим полем',
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();

        if (array_key_exists('birthday', $data) && $data['birthday']) {
            $data['birthday'] = str_replace('/', '-', $data['birthday']);
            $data['birthday'] = Carbon::createFromTimestamp(strtotime($data['birthday']));
        }

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

    public function validated()
    {
        $validated = parent::validated();

        $validated['species_id'] = $validated['species'];
        $validated['breed_id'] = $validated['breed'];
        $validated['color_id'] = $validated['color'];
        $validated['fur_id'] = $validated['fur'];
        unset($validated['species']);
        unset($validated['breed']);
        unset($validated['color']);
        unset($validated['fur']);

        return $validated;
    }

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
