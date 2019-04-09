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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fur query()
 * @mixin \Eloquent
 */
class Fur extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'available'
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
