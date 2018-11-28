<?php

namespace App;

use App\Models\NotificationTemplate;
use App\Models\Organization;
use App\Models\UserAddress;
use App\Models\UserEmail;
use App\Models\UserPhone;
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
 * @property bool $banned
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserAddress[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserEmail[] $emails
 * @property-read mixed $full_name
 * @property-read mixed $living_address
 * @property-read mixed $name
 * @property-read mixed $primary_email
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $history
 * @property-read mixed $additional_mail
 * @property-read mixed $additional_phone
 * @property-read mixed $additional_email
 */
class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    protected $fillable = [
        'id', 'ext_id', 'first_name', 'last_name', 'middle_name', 'birthday',
        'inn', 'passport', 'gender', 'banned', 'organization_id'
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'ext_id' => 'integer',
        'inn' => 'integer',
        'gender' => 'integer',
        'banned' => 'boolean',
    ];

    protected $hidden = [
        'remember_token'
    ];

    public function animals()
    {
        return $this->hasMany('App\Models\Animal');
    }

    public function animalsVerified()
    {
        return $this->animals->where('verified', '=', true);
    }

    public function animalsUnverified()
    {
        return $this->animals->where('verified', '=', false);
    }

    public function getNameAttribute()
    {
        return (($this->last_name) ? $this->last_name : '') . ' '
            . (($this->first_name) ? $this->first_name : '');
    }

    public function getFullNameAttribute()
    {
        return (($this->last_name) ? $this->last_name : '') . ' '
            . (($this->first_name) ? $this->first_name : '') . ' '
            . (($this->middle_name) ? $this->middle_name : '');
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
    public function emailsSystem()
    {
        return $this->emails()->where('type', '<>', UserEmail::TYPE_MANUAL);
    }
    public function emailsAdditional()
    {
        return $this->emails()->where('type', '=', UserEmail::TYPE_MANUAL);
    }

    public function getPrimaryEmailAttribute()
    {
        $email = $this->emails->where('type', UserEmail::TYPE_PRIMARY)->first();
        if (!$email) $email = $this->emails->first();
        return $email;
    }

    public function phones()
    {
        return $this->hasMany('App\Models\UserPhone');
    }
    public function phonesSystem()
    {
        return $this->phones()->where('type', '<>', UserPhone::TYPE_MANUAL);
    }
    public function phonesAdditional()
    {
        return $this->phones()->where('type', '=', UserPhone::TYPE_MANUAL);
    }

    public function history()
    {
        return $this->morphMany('App\Models\Log', 'object');
    }

    public function hasNotification()
    {
        return $this->animalsUnverified()->count() > 0;
    }

    public function getNotification()
    {
        $notification = NotificationTemplate::getByName('animal-verify');
        if ($notification->active) {
            return $notification->fillTextPlaceholders($this);
        } else {
            return false;
        }
    }

    public function getAdditionalPhoneAttribute()
    {
        $phone = $this->phonesAdditional()->first();
        return $phone ? $phone->phone : null ?? '';
    }

    public function getAdditionalEmailAttribute()
    {
        $email = $this->emailsAdditional()->first();
        return $email ? $email->email : null ?? '';
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function getContactInfoAttribute()
    {

        $contactInfo = [
            'contact_name' => $this->first_name,
            'contact_phone' => $this->phones[0]->phone,
            'contact_email' => $this->primary_email->email
        ];

        return json_encode($contactInfo);

    }
}
