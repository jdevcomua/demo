<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Breed
 *
 * @property int $id
 * @property string $name
 * @property int $fci
 * @property int $species_id
 * @property bool $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FoundAnimal[] $foundAnimals
 * @property-read \App\Models\Species $species
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed query()
 * @mixin \Eloquent
 */
class Breed extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'available', 'fci'
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
