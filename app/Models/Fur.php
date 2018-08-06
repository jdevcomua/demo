<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Fur
 *
 * @property int $id
 * @property string $name
 * @property int $species_id
 * @property int $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \App\Models\Species $species
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur whereSpeciesId($value)
 * @mixin \Eloquent
 */
class Fur extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'available'
    ];


    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

    public function species()
    {
        return $this->belongsTo('App\Models\Species');
    }
}
