<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IdentifyingDevice
 *
 * @property int $id
 * @property int $identifying_device_type_id
 * @property int|null $animal_id
 * @property string $number
 * @property string $issued_by
 * @property string|null $info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal|null $animal
 * @property-read \App\Models\IdentifyingDeviceType $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDevice query()
 * @mixin \Eloquent
 */
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
