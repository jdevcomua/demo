<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformAnimalMovedOut extends FormRequest //todo все реквесты нужно называть ...Request
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
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'date.required' => 'Дата вивезення є обов\'язковим полем',
            'date.before' => 'Дата вивезення не може бути у майбутньому!',
            'date.date_format' => 'Дата вивезення повинна бути корректною',
        ];
    }
}
