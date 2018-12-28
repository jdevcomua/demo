<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DeathArchiveRecord extends Model
{
    public function archived()
    {
        return $this->morphMany(Animal::class, 'archived');
    }

    public function getCauseOfDeathAttribute()
    {
        return CauseOfDeath::find($this->cause_of_death_id)->name;
    }

    public function getDiedAtAttribute($value) {
        $carbonInstance = Carbon::parse($value);
        return $carbonInstance->format('d/m/Y');
    }

    public function getCarbonDiedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

}
