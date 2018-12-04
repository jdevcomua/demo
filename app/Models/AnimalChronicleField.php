<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalChronicleField extends Model
{
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
