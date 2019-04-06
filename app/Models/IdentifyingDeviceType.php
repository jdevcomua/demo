<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IdentifyingDeviceType
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IdentifyingDevice[] $identifyingDevice
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDeviceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDeviceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdentifyingDeviceType query()
 * @mixin \Eloquent
 */
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
