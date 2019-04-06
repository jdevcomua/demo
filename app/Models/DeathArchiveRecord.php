<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeathArchiveRecord
 *
 * @property int $id
 * @property int $cause_of_death_id
 * @property string $died_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $archived
 * @property-read mixed $carbon_died_at
 * @property-read mixed $cause_of_death
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathArchiveRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathArchiveRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathArchiveRecord query()
 * @mixin \Eloquent
 */
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
        //todo protected $dates делает то же самое
        return Carbon::parse($value);
    }

}
