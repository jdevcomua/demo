<?php

namespace App;

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
 * @property string $email
 * @property string|null $phone
 * @property string $full_name
 * @property \Carbon\Carbon $birthday
 * @property int $inn
 * @property string $passport
 * @property array $address_living
 * @property array $address_registration
 * @property int $gender
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAddressLiving($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAddressRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $name
 */
class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    protected $fillable = [
        'ext_id', 'first_name', 'last_name', 'middle_name', 'email', 'phone', 'birthday',
        'inn', 'passport', 'address_living', 'address_registration', 'gender'
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

    public function getAddressLivingAttribute()
    {
        return (object) json_decode($this->attributes['address_living']);
    }

    public function getAddressRegistrationAttribute()
    {
        return (object) json_decode($this->attributes['address_registration']);
    }

    public function getFullNameAttribute ()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->middle_name;
    }
}
