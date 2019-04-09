<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VeterinaryMeasure
 *
 * @property int $id
 * @property string $name
 * @property int $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalVeterinaryMeasure[] $animalVeterinaryMeasures
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryMeasure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryMeasure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryMeasure query()
 * @mixin \Eloquent
 */
class VeterinaryMeasure extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function animalVeterinaryMeasures()
    {
        return $this->hasMany(AnimalVeterinaryMeasure::class);
    }
}
