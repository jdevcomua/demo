<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sterilization extends Model
{
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];


    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
