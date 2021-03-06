<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property int $user_id
 * @property int $action
 * @property int $status
 * @property int $finished
 * @property int|null $object_id
 * @property string|null $object_type
 * @property string|null $changes
 * @property string|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $action_name
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $object
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log query()
 * @mixin \Eloquent
 */
class Log extends Model
{
    const ACTION_CREATE = 1;
    const ACTION_EDIT = 2;
    const ACTION_DELETE = 3;
    const ACTION_REGISTER = 4;
    const ACTION_LOGIN = 5;
    const ACTION_VERIFY = 6;
    const ACTION_VERIFY_CANCEL = 7;
    const ACTION_IDEVICE_ADDED = 8;
    const ACTION_IDEVICE_REMOVED = 9;
    const ACTION_STERILIZATION_ADDED = 10;
    const ACTION_VACCINATION_ADDED = 11;
    const ACTION_VET_MEASURE_ADDED = 12;
    const ACTION_OFFENSE_ADDED = 13;
    const ACTION_ANIMAL_DEATH = 14;
    const ACTION_ANIMAL_MOVED = 15;
    const ACTION_ANIMAL_CHANGE_OWNER = 16;
    const ACTION_ANIMAL_LOST = 17;
    const ACTION_ANIMAL_FOUND = 18;


    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    const ACTIONS = [
        self::ACTION_CREATE => 'Створення',
        self::ACTION_EDIT => 'Редагування',
        self::ACTION_DELETE => 'Видалення',
        self::ACTION_REGISTER => 'Реєстрація',
        self::ACTION_LOGIN => 'Авторизація',
        self::ACTION_VERIFY => 'Верифікація',
        self::ACTION_VERIFY_CANCEL => 'Відміна верифікації',
        self::ACTION_IDEVICE_ADDED => 'Ідентифікуючий пристрій додано',
        self::ACTION_IDEVICE_REMOVED => 'Ідентифікуючий пристрій видалено',
        self::ACTION_STERILIZATION_ADDED => 'Стерилізацію додано',
        self::ACTION_VACCINATION_ADDED => 'Вакцинацію додано',
        self::ACTION_VET_MEASURE_ADDED => 'Ветеринарний захід додано',
        self::ACTION_OFFENSE_ADDED => 'Правопорушення додано',
        self::ACTION_ANIMAL_DEATH => 'Фіксація смерті тварини',
        self::ACTION_ANIMAL_MOVED => 'Фіксація виїзду тварини',
        self::ACTION_ANIMAL_CHANGE_OWNER => 'Передача іншому власнику',
        self::ACTION_ANIMAL_LOST => 'Тварину загублено',
        self::ACTION_ANIMAL_FOUND => 'Тварину знайдено',
    ];

    const STATUSES = [
        self::STATUS_ERROR => 'Помилка',
        self::STATUS_OK => 'Ок',
    ];

    public static $idsObjectTypesMap = [
        'organization_id' => ['morph_name' => 'Організація', 'name' => 'Організація'],
        'animal_id' => ['morph_name' => 'Тварина', 'name' => 'Тварина'] ,
        'new_owner' => ['morph_name' => 'Користувач', 'name' => 'Власник']
    ];

    /**
     * @var array
     * key is id to replace with data to display
     * key => array(name_of_model, name_of_column_from_the_model)
     */
    public static $idsModelsToDisplay = [
        'veterinary_measure_id' => [
            'name' => 'Ветеринарний захід',
            'model' => 'VeterinaryMeasure',
            'column_name' => 'name'
        ],
        'offense_id' => [
            'name' => 'Правопорушення',
            'model' => 'Offense',
            'column_name' => 'name'
        ],
        'offense_affiliation_id' => [
            'name' => 'Належність правопорушення',
            'model' => 'OffenseAffiliation',
            'column_name' => 'name'
        ],
        'cause_of_death_id' => [
            'name' => 'Причина смерті',
            'model' => 'CauseOfDeath',
            'column_name' => 'name'
        ]
    ];


    protected $fillable = [
        'id', 'user_id', 'action', 'status', 'finished', 'object_id',
        'object_type', 'changes', 'payload'
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getActionNameAttribute()
    {
        if (array_key_exists($this->action, self::ACTIONS)) {
            return self::ACTIONS[$this->action];
        } else {
            return '?';
        }
    }

    public function getStatusNameAttribute()
    {
        if (array_key_exists($this->status, self::STATUSES)) {
            return self::STATUSES[$this->status];
        } else {
            return '?';
        }
    }

    public function object()
    {
        return $this->morphTo('object', 'object_type', 'object_id');
    }

}
