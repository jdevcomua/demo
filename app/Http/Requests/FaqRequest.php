<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    protected $errorBag = 'faq';

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
            'question' => 'required|string|max:1000',
            'answer' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'Питання є обов\'язковим полем',
            'question.max' => 'Питання має бути менше :max символів',
            'answer.required' => 'Відповідь є обов\'язковим полем'
        ];
    }
}
