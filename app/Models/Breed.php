<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Breed
 *
 * @property int $id
 * @property string $name
 * @property string|null $fci
 * @property int $species_id
 * @property bool $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \App\Models\Species $species
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed whereFci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Breed whereSpeciesId($value)
 * @mixin \Eloquent
 */
class Breed extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'available', 'fci'
    ];

    protected $casts = [
        'available' => 'boolean',
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
