<?php

namespace App\Http\Requests;

use App\Models\NotificationTemplate;
use Illuminate\Foundation\Http\FormRequest;

class NotificationTemplateRequest extends FormRequest
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
            'name' => 'required|alpha_dash|max:255|unique:notification_templates,name,' . $this->route('id'),
            'type' => 'nullable|integer|in:'.implode(',',array_keys(NotificationTemplate::getTypes())),
            //TODO write rest of the rules
        ];
    }
}
