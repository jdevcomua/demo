<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sterilization
 *
 * @property int $id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $description
 * @property string $made_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sterilization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sterilization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sterilization query()
 * @mixin \Eloquent
 */
class Sterilization extends Model
{
    protected $fillable = [
        'date', 'made_by', 'description'
    ];

    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    protected $name = 'Стерилізація';


    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function getNameAttribute()
    {
        return $this->name;
    }
}
