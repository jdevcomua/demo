<?php

namespace App\Http\Requests;

use App\Models\Animal;
use App\Models\DeathArchiveRecord;
use App\Models\MovedOutArchiveRecord;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ArchiveAnimal extends FormRequest

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
            'archive_type' => 'required',
            'date' => 'required|date_format:d/m/Y|before:tomorrow',
            ];

        if ($this['archive_type'] === 'death') {
            $rules['cause_of_death'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'archive_type.required' => 'Причина архівації є обов\'язковим полем',
            'cause_of_death.required' => 'Причина смерті є обов\'язковим полем',
            'date.required' => 'Дата є обов\'язковим полем',
            'date.before' => 'Дата не може бути у майбутньому!',
            'date.date_format' => 'Дата повинна бути корректною',
        ];
    }

    public function persist(Animal $animal): void
    {
        $requestData = $this->all();
        $requestData['date'] = Carbon::createFromFormat('d/m/Y', $requestData['date'])->toDateString();

        $animal->archived_at = now();

        if ($requestData['archive_type'] === 'moved_out') {
            $archiveRecord = new MovedOutArchiveRecord;
            $archiveRecord->moved_out_at = $requestData['date'];
        } else {
            $archiveRecord = new DeathArchiveRecord;
            $archiveRecord->died_at = $requestData['date'];
            $archiveRecord->cause_of_death_id = $requestData['cause_of_death'];
        }

        $archiveRecord->save();

        $archiveRecord->archived()->save($animal);
    }
}
