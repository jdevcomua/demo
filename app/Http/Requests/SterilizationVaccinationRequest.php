<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SterilizationVaccinationRequest extends FormRequest
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
            'date' => 'required|date_format:d/m/Y|before:tomorrow',
            'made_by' => 'required|string'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'date.required' => 'Дата проведення є обов\'язковим полем!',
            'date.before' => 'Дата проведення не може бути у майбутньому!',
            'date.date_format' => 'Дата проведення повинна бути корректною!',
            'made_by.required' => 'Поле \'Ким проведено\' є обов\'язковим! ',
            'made_by.string' => 'Поле \'Ким проведено\' має бути строкою! ',
        ];
    }
}
