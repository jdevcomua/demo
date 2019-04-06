<?php

namespace App\Models;

use App\Helpers\ProcessedCache;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChangeAnimalOwner
 *
 * @property int $id
 * @property int $processed
 * @property int $animal_id
 * @property string $passport
 * @property string $full_name
 * @property string $contact_phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeAnimalOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeAnimalOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChangeAnimalOwner query()
 * @mixin \Eloquent
 */
class ChangeAnimalOwner extends Model
{
    use ProcessedCache;

    protected $fillable = ['id', 'full_name', 'processed', 'passport', 'contact_phone', 'animal_id'];
}
