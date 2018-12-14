<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalVeterinaryMeasureFile extends Model
{
    protected $fillable = ['animal_veterinary_measure_id', 'path', 'name'];

    public function animalVeterinaryMeasure()
    {
        return $this->belongsTo(AnimalVeterinaryMeasure::class);
    }
}
