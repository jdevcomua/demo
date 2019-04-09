<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalVeterinaryMeasure
 *
 * @property int $id
 * @property int $veterinary_measure_id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $description
 * @property string $made_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalVeterinaryMeasureFile[] $files
 * @property-read \App\Models\VeterinaryMeasure $veterinaryMeasure
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasure query()
 * @mixin \Eloquent
 */
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
