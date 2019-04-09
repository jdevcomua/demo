<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CauseOfDeath
 *
 * @property int $id
 * @property string $name
 * @property int $available
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CauseOfDeath newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CauseOfDeath newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CauseOfDeath query()
 * @mixin \Eloquent
 */
class CauseOfDeath extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'available'];

    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

}
