<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $ext_user_id
 * @property int $type_registration_id
 * @property string $first_name
 * @property string $second_name
 * @property string|null $middle_name
 * @property string|null $email
 * @property string|null $phone
 * @property \Carbon\Carbon $birthday
 * @property int $gender
 * @property array $data
 * @property string|null $passport
 * @property string|null $residence_address
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExtUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereResidenceAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSecondName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTypeRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'ext_user_id', 'type_registration_id', 'first_name', 'second_name',
        'middle_name', 'email', 'phone', 'birthday', 'gender', 'data',
        'passport', 'residence_address'
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'type_registration_id' => 'integer',
        'gender' => 'integer',
        'data' => 'array',
    ];


    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }
}
