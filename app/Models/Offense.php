<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Offense
 *
 * @property int $id
 * @property string $name
 * @property int $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalOffense[] $animalOffenses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offense query()
 * @mixin \Eloquent
 */
class Offense extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function animalOffenses()
    {
        return $this->hasMany(AnimalOffense::class);
    }
}
