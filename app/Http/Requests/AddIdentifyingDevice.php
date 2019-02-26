<?php

namespace App\Http\Requests;

use App\Models\Animal;
use Illuminate\Foundation\Http\FormRequest;

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
        $data = $this->all();

        $rulesByTypes = [
            Animal::IDENTIFYING_DEVICES_TYPE_CLIP => 'required',
            Animal::IDENTIFYING_DEVICES_TYPE_CHIP => 'required|size:15',
            Animal::IDENTIFYING_DEVICES_TYPE_BADGE => 'required|between:5,8',
            Animal::IDENTIFYING_DEVICES_TYPE_BRAND => 'required',
        ];


        $rules = [
            'device_type' => 'required|in:' . implode(',', array_keys($rulesByTypes)),
            'info' => 'nullable|string|max:255',
            'issued_by' => 'string|max:255'
        ];

        if(isset($rulesByTypes[$data['device_type']])) {
            $rules['number'] = $rulesByTypes[$data['device_type']];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'device_type.required' => 'Тип пристрою є обов\'язковим полем!',
            'number.required' => 'Номер пристрою є обов\'язковим полем!',
            'number.size' => 'Номер даного типу пристрою повинен складатися з :size символів!',
            'number.between' => 'Номер даного типу пристрою повинен бути не менше :min символів та не більше :max символів!',
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
