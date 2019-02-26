<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformAnimalDeath extends FormRequest //todo все реквесты нужно называть ...Request
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
            'cause_of_death' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'cause_of_death.required' => 'Причина смерті є обов\'язковим полем',
            'date.required' => 'Дата смерті є обов\'язковим полем',
            'date.before' => 'Дата смерті не може бути у майбутньому!',
            'date.date_format' => 'Дата смерті повинна бути корректною',
        ];
    }
}
