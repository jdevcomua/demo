<?php

namespace App\Models;

use App\Helpers\ProcessedCache;
use Illuminate\Database\Eloquent\Model;

class ChangeAnimalOwner extends Model
{
    use ProcessedCache;

    protected $fillable = ['id', 'full_name', 'processed', 'passport', 'contact_phone', 'animal_id'];
}
