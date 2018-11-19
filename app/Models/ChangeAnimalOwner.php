<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeAnimalOwner extends Model
{
    protected $fillable = ['id', 'full_name', 'processed', 'passport', 'contact_phone', 'animal_id'];
}
