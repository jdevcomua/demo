<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalChronicle
 *
 * @property int $id
 * @property int $animal_id
 * @property int $animal_chronicle_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalChronicleFieldValue[] $fieldValues
 * @property-read mixed $date
 * @property-read mixed $text
 * @property-write mixed $fields
 * @property \App\Models\AnimalChronicleType $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicle query()
 * @mixin \Eloquent
 */
class AnimalChronicle extends Model
{
    //todo чтоб не мучаться с геттерами\сеттерами есть вариант вызвать свойство $dates и перечислить все поля дат там
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

    public static function getChronicleFieldByType($type): string
    {
        $types = [
            Animal::IDENTIFYING_DEVICES_TYPE_CLIP => 'clip',
            Animal::IDENTIFYING_DEVICES_TYPE_CHIP => 'chip',
            Animal::IDENTIFYING_DEVICES_TYPE_BADGE => 'badge',
            Animal::IDENTIFYING_DEVICES_TYPE_BRAND => 'brand',
        ];

        return $types[$type];
    }
}
