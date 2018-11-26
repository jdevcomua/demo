<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\LostAnimal
 *
 * @property int $id
 * @property int $animal_id
 * @property int $found
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal whereFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LostAnimal extends Model
{
    use Sortable;

    protected $fillable = ['id', 'found', 'created_at', 'animal_id', 'processed'];

    protected $casts = [
        'found' => 'boolean',
    ];

    public $sortable = ['created_at'];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function getLostAtAttribute()
    {
        return $this->created_at;
    }

}
