<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentifyingDeviceType extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    const TYPE_CHIP = 1;
    const TYPE_CLIP = 2;
    const TYPE_BADGE = 3;
    const TYPE_BRAND = 4;

    public function identifyingDevice()
    {
        return $this->hasMany(IdentifyingDevice::class);
    }
}
