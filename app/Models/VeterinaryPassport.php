<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeterinaryPassport extends Model
{
    protected $fillable = ['number', 'issued_by'];

    public function animal()
    {
        return $this->hasOne(Animal::class);
    }
}
