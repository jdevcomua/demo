<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddIdentifyingDevice extends FormRequest
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
        return [
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('identifying_devices','number')->where(function ($query) {
                    return $query->where('identifying_device_type_id', $this->get('device_type'));
                })
            ],
            'device_type' => 'required|exists:identifying_device_types,id',
            'info' => 'nullable|string|max:255',
            'issued_by' => 'string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'device_type.required' => 'Тип пристрою є обов\'язковим полем!',
            'number.required' => 'Номер пристрою є обов\'язковим полем!',
            'number.max' => 'Номер пристрою повинен бути не більше :max символів!',
            'number.unique' => 'Пристрій з таким номером уже існує',
            'info.string' => 'Додаткова інформація повинна бути строкою',
            'info.max' => 'Додаткова інформація не повинна містити більше ніж :max символів',
            'issued_by.string' => 'Ким видано повинно бути строкою',
            'issued_by.max' => 'Ким видано не повинно містити більше ніж :max символів',
        ];
    }

    public function validated()
    {
        $validated = parent::validated();
        $validated['identifying_device_type_id'] = $validated['device_type'];
        return $validated;
    }
}
