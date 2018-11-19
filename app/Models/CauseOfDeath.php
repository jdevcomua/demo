<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseOfDeath extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'available'];

    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

}
