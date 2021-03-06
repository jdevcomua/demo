<?php

namespace App\Models;

use App\Helpers\ProcessedCache;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\LostAnimal
 *
 * @property int $id
 * @property int $animal_id
 * @property bool $found
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $processed
 * @property-read \App\Models\Animal $animal
 * @property-read mixed $lost_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LostAnimal sortable($defaultParameters = null)
 * @mixin \Eloquent
 */
class LostAnimal extends Model
{
    use Sortable, ProcessedCache;

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
