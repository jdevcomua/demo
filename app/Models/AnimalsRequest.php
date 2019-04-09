<?php

namespace App\Models;

use App\Helpers\ProcessedCache;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalsRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $breed_id
 * @property int|null $animal_id
 * @property int|null $color_id
 * @property int|null $fur_id
 * @property int $gender
 * @property int|null $species_id
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $street
 * @property string|null $building
 * @property string|null $apartment
 * @property string|null $nickname
 * @property int $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal|null $animal
 * @property-read \App\Models\Breed|null $breed
 * @property-read \App\Models\Color|null $color
 * @property-read \App\Models\Fur|null $fur
 * @property-read \App\Models\Species|null $species
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest query()
 * @mixin \Eloquent
 */
class AnimalsRequest extends Model
{
    use ProcessedCache;

    protected $fillable = [
        'id',
        'user_id',
        'animal_id',
        'breed_id',
        'color_id',
        'fur_id',
        'species_id',
        'gender',
        'birthday',
        'street',
        'building',
        'apartment',
        'nickname',
        'processed',
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function fur()
    {
        return $this->belongsTo(Fur::class);
    }

}
