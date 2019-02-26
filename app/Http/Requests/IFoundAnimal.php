<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IFoundAnimal extends FormRequest //todo все реквесты нужно называть ...Request + закос под Apple
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
        $allData = $this->all();

        $rules = [
            'badge' => 'nullable|min:5|max:8',
            'species' => 'required|integer|exists:species,id',
            'breed' => 'nullable|exists:breeds,id',
            'color' => 'nullable|exists:colors,id',
            'found_address' => 'required|string|max:255',
            'additional_info' => 'nullable|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'contact_email' => 'nullable|email|string|max:255',
            'documents' => 'nullable|array|max:2',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png|max:2048',
        ];

        if ($allData['only_badge'] !== "0") {
            $rules = [
                'badge' => 'required|min:5|max:8',
                'contact_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:255',
                'contact_email' => 'nullable|email|string|max:255',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'badge.min' => 'Номер жетону повинен складатися мінімум з 5 символів',
            'badge.max' => 'Номер жетону повинен складатися максимум з 8 символів',
            'badge.required' => 'Номер жетону є обов\'язковим полем',
            'species.required' => 'Вид є обов\'язковим полем',
            'species.exists' => 'Не існує такого виду',
            'breed.exists' => 'Не існує такої породи',
            'color.exists' => 'Не існує такої масті',
            'found_address.required' => 'Адреса де знайшли тварину є обов\'язковим полем',
            'found_address.max' => 'Адреса де знайшли тварину не повинна перевищувати :max символів',
            'additional_info.max' => 'Додаткова інформація не повинна перевищувати :max символів',
            'contact_name.required' => 'Ім\'я є обов\'язковим полем',
            'contact_name.max' => 'Ім\'я не повинно перевищувати :max символів',
            'contact_phone.required' => 'Телефон є обов\'язковим полем',
            'contact_phone.max' => 'Телефон не повинен перевищувати :max символів',
            'contact_email.email' => 'E-mail повинен бути валідним',
            'documents.max' => 'Максимальна кількість фотографій не повинна перевищувати 9 файлів',
            'documents.*.max' => 'Фото повинні бути не більше 2Mb',
            'documents.*.mimes' => 'Фото повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png',
        ];
    }
}
