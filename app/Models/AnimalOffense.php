<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalOffense
 *
 * @property int $id
 * @property int $offense_id
 * @property int $offense_affiliation_id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon $protocol_date
 * @property string $protocol_number
 * @property string|null $description
 * @property int $bite
 * @property string $made_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalOffenseFile[] $files
 * @property-read \App\Models\Offense $offense
 * @property-read \App\Models\OffenseAffiliation $offenseAffiliation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalOffense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalOffense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalOffense query()
 * @mixin \Eloquent
 */
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
