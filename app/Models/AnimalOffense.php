<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalOffense extends Model
{
    protected $dates = [
        'date', 'protocol_date', 'created_at', 'updated_at'
    ];

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }

    public function offenseAffiliation()
    {
        return $this->belongsTo(OffenseAffiliation::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function files()
    {
        return $this->hasMany(AnimalOffenseFile::class);
    }
}
