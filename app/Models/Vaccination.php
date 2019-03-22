<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vaccination
 *
 * @property int $id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $description
 * @property string $made_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereMadeBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vaccination whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vaccination extends Model
{
    protected $fillable = [
        'date', 'made_by', 'description'
    ];

    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    protected $name = 'Щеплення від сказу';

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function getNameAttribute()
    {
        return $this->name;
    }
}
