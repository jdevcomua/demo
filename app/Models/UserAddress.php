<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $country
 * @property string $country_code
 * @property string $state
 * @property string $city
 * @property string $district
 * @property string $street
 * @property string $building
 * @property string $apartment
 * @property string $postcode
 * @property string $lat
 * @property string $lon
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereType($value)
 */
class UserAddress extends Model
{
    const ADDRESS_TYPE_LIVING = 'FACTUAL';
    const ADDRESS_TYPE_REGISTRATION = 'REGISTRATION';

    protected $fillable = [
        'type', 'country', 'country_code', 'state', 'city', 'district', 'street',
        'building', 'apartment', 'postcode', 'lat', 'lon',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
