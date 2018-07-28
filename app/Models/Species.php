<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Species
 *
 * @property int $id
 * @property string $name
 * @property bool $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Breed[] $breeds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Color[] $colors
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Species whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Species whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Species whereName($value)
 * @mixin \Eloquent
 */
class Species extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'available'
    ];

    protected $casts = [
        'available' => 'boolean',
    ];


    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

    public function breeds()
    {
        return $this->hasMany('App\Models\Breed');
    }

    public function colors()
    {
        return $this->hasMany('App\Models\Color');
    }
}
