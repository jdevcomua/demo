<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalChronicleFieldValue
 *
 * @property int $id
 * @property string $field_value
 * @property int $animal_chronicle_field_id
 * @property int $animal_chronicle_id
 * @property-read \App\Models\AnimalChronicle $chronicle
 * @property-read \App\Models\AnimalChronicleField $field
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleFieldValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleFieldValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleFieldValue query()
 * @mixin \Eloquent
 */
class AnimalChronicleFieldValue extends Model
{
    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(AnimalChronicleField::class, 'animal_chronicle_field_id');
    }

    public function chronicle()
    {
        return $this->belongsTo(AnimalChronicle::class);
    }
}
