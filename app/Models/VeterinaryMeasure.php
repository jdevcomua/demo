<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeterinaryMeasure extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function animalVeterinaryMeasures()
    {
        return $this->hasMany(AnimalVeterinaryMeasure::class);
    }
}
