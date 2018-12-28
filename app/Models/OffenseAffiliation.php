<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffenseAffiliation extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function animalOffenses()
    {
        return $this->hasMany(AnimalOffense::class);
    }
}
