<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VeterinaryPassport
 *
 * @property int $id
 * @property string $number
 * @property string $issued_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryPassport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryPassport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VeterinaryPassport query()
 * @mixin \Eloquent
 */
class VeterinaryPassport extends Model
{
    protected $fillable = ['number', 'issued_by'];

    public function animal()
    {
        return $this->hasOne(Animal::class);
    }
}
