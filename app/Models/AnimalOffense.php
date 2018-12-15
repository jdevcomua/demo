<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalOffense extends Model
{
    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }

    public function offenseAffiliation()
    {
        return $this->belongsTo(OffenseAffiliation::class);
    }
}
