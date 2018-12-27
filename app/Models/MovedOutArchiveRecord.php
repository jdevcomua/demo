<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MovedOutArchiveRecord extends Model
{
    public function archived()
    {
        return $this->morphMany(Animal::class, 'archived');
    }

    public function getMovedOutAtAttribute($value) {
        $carbonInstance = Carbon::parse($value);
        return $carbonInstance->format('d/m/Y');
    }

    public function getCarbonDiedAtAttribute($value)
    {
        return Carbon::parse($value);
    }
}
