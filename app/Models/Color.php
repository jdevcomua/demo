<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Color
 *
 * @property int $id
 * @property string $name
 * @property int $species_id
 * @property bool $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \App\Models\Species $species
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereSpeciesId($value)
 * @mixin \Eloquent
 */
class Color extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'available'
    ];

    protected $casts = [
        'available' => 'boolean',
    ];


    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

    public function foundAnimals()
    {
        return $this->hasMany('App\Models\FoundAnimal');
    }

    public function species()
    {
        return $this->belongsTo('App\Models\Species');
    }
}
