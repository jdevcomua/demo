<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentifyingDevice extends Model
{
    protected $fillable = ['identifying_device_type_id', 'animal_id', 'number', 'issued_by', 'info'];

    public function type()
    {
        return $this->belongsTo(IdentifyingDeviceType::class, 'identifying_device_type_id');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
