<?php


namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalsRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $breed_id
 * @property int|null $color_id
 * @property int|null $animal_id
 * @property int|null $fur_id
 * @property int|null $species_id
 * @property string|null $birthday
 * @property string|null $street
 * @property string|null $building
 * @property string|null $apartment
 * @property string|null $nickname
 * @property int $processed
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Animal|null $animal
 * @property-read \App\Models\Breed|null $breed
 * @property-read \App\Models\Color|null $color
 * @property-read \App\Models\Fur|null $fur
 * @property-read \App\Models\Species|null $species
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereFurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereSpeciesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereUserId($value)
 * @mixin \Eloquent
 * @property int $gender
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsRequest whereGender($value)
 */
class AnimalsRequest extends Model
{
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
