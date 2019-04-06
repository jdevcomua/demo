<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $country
 * @property string|null $country_code
 * @property string|null $state
 * @property string|null $city
 * @property string|null $district
 * @property string|null $street
 * @property string|null $building
 * @property string|null $apartment
 * @property string|null $postcode
 * @property string|null $lat
 * @property string|null $lon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $full_address
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress query()
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
    const ADDRESS_TYPE_LIVING = 'FACTUAL';
    const ADDRESS_TYPE_REGISTRATION = 'REGISTRATION';

    protected $fillable = [
        'type', 'country', 'country_code', 'state', 'city', 'district', 'street',
        'building', 'apartment', 'postcode', 'lat', 'lon',
    ];

    public function getFullAddressAttribute()
    {
        return $this->city . ', ' . $this->street . ' ' . $this->building . ', ' . $this->apartment;
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
