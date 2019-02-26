<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentifyingDeviceType extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function identifyingDevice()
    {
        return $this->hasMany(IdentifyingDevice::class);
    }
}
