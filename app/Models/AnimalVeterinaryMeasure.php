<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalVeterinaryMeasure extends Model
{
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function veterinaryMeasure()
    {
        return $this->belongsTo(VeterinaryMeasure::class);
    }

    public function files()
    {
        return $this->hasMany(AnimalVeterinaryMeasureFile::class);
    }
}
