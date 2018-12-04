<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AnimalChronicle extends Model
{
    const FIELD_BADGE = 'badge';
    const FIELD_CLIP = 'clip';
    const FIELD_CHIP = 'chip';

    const TYPE_ADDED_BADGE = 'badge-added';
    const TYPE_ADDED_CLIP =  'clip-added';
    const TYPE_ADDED_CHIP =  'chip-added';

    const TYPE_REMOVED_BADGE =  'badge-removed';
    const TYPE_REMOVED_CLIP =  'clip-removed';
    const TYPE_REMOVED_CHIP =  'chip-removed';

    public function type()
    {
        return $this->belongsTo(AnimalChronicleType::class, 'animal_chronicle_type_id');
    }

    public function fieldValues()
    {
        return $this->hasMany(AnimalChronicleFieldValue::class, 'animal_chronicle_id');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function setTypeAttribute(string $type)
    {
        $chronicleType = AnimalChronicleType::where(['type' => $type])->first();
        $this->animal_chronicle_type_id = $chronicleType->id;
    }

    public function setFieldsAttribute (array $field_values)
    {
        $chronicleFields = AnimalChronicleField::where(['animal_chronicle_type_id' => $this->animal_chronicle_type_id])->get();

        foreach ($field_values as $k => $v)
        {
            $chronicleFieldValue = new AnimalChronicleFieldValue;
            $chronicleFieldValue->field_value = $v;
            $chronicleFieldValue->animal_chronicle_field_id = $chronicleFields->whereStrict('field_name', $k)->first()->id;
            $chronicleFieldValue->animal_chronicle_id = $this->id;
            $chronicleFieldValue->save();
        }
    }

    public function getDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d.m.Y');
    }

    public function getTextAttribute()
    {
        $text = $this->type->template_text;

        if (count($this->fieldValues)) {
            foreach ($this->fieldValues as $fieldValue) {
                $text = $this->replaceWithValue($text, $fieldValue->field->field_name, $fieldValue->field_value);
            }
        }
        return $text;
    }

    private function replaceWithValue($text, $key, $value)
    {
        return str_replace('{' . $key . '}', $value, $text);
    }
}
