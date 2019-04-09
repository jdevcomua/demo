<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalChronicleField
 *
 * @property int $id
 * @property int $animal_chronicle_type_id
 * @property string $field_name
 * @property-read \App\Models\AnimalChronicleType $type
 * @property-read \App\Models\AnimalChronicleFieldValue $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleField query()
 * @mixin \Eloquent
 */
class AnimalChronicleField extends Model
{
    protected $fillable = [ 'animal_chronicle_type_id', 'field_name' ];

    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(AnimalChronicleType::class);
    }

    public function value()
    {
        return $this->hasOne(AnimalChronicleFieldValue::class);
    }
}
