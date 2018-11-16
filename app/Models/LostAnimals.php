<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostAnimals extends Model
{
    protected $fillable = ['id', 'found', 'created_at', 'animal_id'];

    const FOUND_NO = 0;
    const FOUND_YES = 1;

}
