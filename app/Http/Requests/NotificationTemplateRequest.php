<?php

namespace App\Http\Requests;

use App\Models\NotificationTemplate;
use App\Rules\EventIsset;
use Illuminate\Foundation\Http\FormRequest;

class NotificationTemplateRequest extends FormRequest
{

    protected $errorBag = 'notification';
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
            'name' => 'required|alpha_dash|max:255|unique:notification_templates,name,' . $this->route('id'),
            'type' => 'nullable|integer|in:'.implode(',',array_keys(NotificationTemplate::getTypes(false))),
            'subject' => 'nullable|string|max:255',
            'body' => 'required_unless:type,'.implode(',',NotificationTemplate::getTypesWithHtmlBody()),
            'bodyHtml' => 'required_if:type,'.implode(',',NotificationTemplate::getTypesWithHtmlBody()),
            'events' => 'nullable|array',
            'events.*' => [
                'string',
                new EventIsset()
            ],
            'active' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Назва є обов\'язковим полем',
            'name.alpha_dash' => 'Назва повинна складатися тільки з кириличних літер, цифр, \'-\' та \'_\'',
            'name.max' => 'Назва має бути менше :max символів',
            'name.unique' => 'Така назва вже використовується',
            'subject.max' => 'Тема має бути менше :max символів',
        ];
    }

    public function validated()
    {
        $data = parent::validated();

        if (isset($data['events'])) {
            $data['events'] = implode('@', $data['events']);
        }

        if (isset($data['type']) && array_search($data['type'],
                NotificationTemplate::getTypesWithHtmlBody()) !== false) {

            $data['body'] = $data['bodyHtml'];
        }
        unset($data['bodyHtml']);

        $data['active'] = array_key_exists('active', $data);

        return $data;
    }
}
