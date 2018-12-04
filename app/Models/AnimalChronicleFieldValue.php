<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
