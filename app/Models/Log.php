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
 * @property string|null $object
 * @property string|null $changes
 * @property string|null $payload
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $action_name
 * @property-read mixed $status_name
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereUserId($value)
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

    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    const ACTIONS = [
        self::ACTION_CREATE => 'Створення',
        self::ACTION_EDIT => 'Редагування',
        self::ACTION_DELETE => 'Видалення',
        self::ACTION_REGISTER => 'Реєстрація',
        self::ACTION_LOGIN => 'Авторизація',
        self::ACTION_VERIFY => 'Верифікація',
    ];

    const STATUSES = [
        self::STATUS_ERROR => 'Помилка',
        self::STATUS_OK => 'Ок',
    ];


    protected $fillable = [
        'user_id', 'action', 'status', 'finished', 'object_id', 'object_type', 'changes', 'payload'
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
