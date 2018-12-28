<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
