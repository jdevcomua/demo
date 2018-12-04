<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalChronicleType extends Model
{
    public $timestamps = false;

    public function fields()
    {
        return $this->hasMany(AnimalChronicleField::class);
    }

    public function chronicle()
    {
        return $this->hasOne(AnimalChronicle::class);
    }

}
