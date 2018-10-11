<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NotificationTemplate
 *
 * @property int $id
 * @property int $type
 * @property string $name
 * @property string|null $subject
 * @property string $body
 * @property int $active
 * @property string|null $events
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NotificationTemplate extends Model
{

    const TYPE_EMAIL = 0;
    const TYPE_SYSTEM = 1;
    const TYPE_ALERT = 2;

    protected $fillable = [
        'id', 'type', 'name', 'subject', 'body', 'active', 'events'
    ];

    public static function getTypes($withSystem = true)
    {
        $res = [
            self::TYPE_EMAIL => 'E-mail',
            self::TYPE_ALERT => 'Alert'
        ];

        if ($withSystem) $res[self::TYPE_SYSTEM] = 'System';

        return $res;
    }

    public static function getDescription()
    {
        return [
            self::TYPE_EMAIL => 'відправляється на електронну пошту',
            self::TYPE_SYSTEM => 'завжди відображається на сторінці',
            self::TYPE_ALERT => 'відображається в меню нотифікацій'
        ];
    }

    public function isSystem()
    {
        return $this->type === self::TYPE_SYSTEM;
    }

    public function hasEvent($eventClass)
    {
        return array_search($eventClass,
                explode('@', $this->events)) !== false;
    }
}
