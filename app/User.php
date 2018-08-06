<?php

namespace App;

use App\Models\UserAddress;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * App\User
 *
 * @property int $id
 * @property int $ext_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property \Carbon\Carbon $birthday
 * @property int $inn
 * @property string $passport
 * @property int $gender
 * @property int $banned
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserAddress[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \App\User $bannedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserEmail[] $emails
 * @property-read mixed $living_address
 * @property-read mixed $name
 * @property-read mixed $registration_address
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserPhone[] $phones
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    protected $fillable = [
        'ext_id', 'first_name', 'last_name', 'middle_name', 'email', 'phone', 'birthday',
        'inn', 'passport', 'address_living', 'address_registration', 'gender', 'banned'
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'ext_id' => 'integer',
        'inn' => 'integer',
        'gender' => 'integer',
        'address_living' => 'array',
        'address_registration' => 'array',
        'banned' => 'boolean',
    ];

    protected $hidden = [
        'remember_token', 'address_living', 'address_registration', 'inn'
    ];

    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

    public function getNameAttribute()
    {
        return (($this->last_name) ? $this->last_name : '') . ' '
            . (($this->first_name) ? $this->first_name : '');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\UserAddress');
    }

    public function getLivingAddressAttribute()
    {
        return $this->addresses->where('type', '=', UserAddress::ADDRESS_TYPE_LIVING)->first();
    }

    public function getRegistrationAddressAttribute()
    {
        return $this->addresses->where('type', '=', UserAddress::ADDRESS_TYPE_REGISTRATION)->first();
    }

    public function emails()
    {
        return $this->hasMany('App\Models\UserEmail');
    }

    public function phones()
    {
        return $this->hasMany('App\Models\UserPhone');
    }
}
