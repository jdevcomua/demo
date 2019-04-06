<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MovedOutArchiveRecord
 *
 * @property int $id
 * @property string $moved_out_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $archived
 * @property-read mixed $carbon_died_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovedOutArchiveRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovedOutArchiveRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovedOutArchiveRecord query()
 * @mixin \Eloquent
 */
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
        //todo private $dates
        return Carbon::parse($value);
    }
}
