<?php

namespace App\Models;

use App\User;
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

    public static function getTypesWithHtmlBody()
    {
        return [
            self::TYPE_EMAIL,
        ];
    }

    public static function getTypesWithTitle()
    {
        return [
            self::TYPE_EMAIL,
            self::TYPE_ALERT,
        ];
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

    /**
     * @param string $name
     * @return NotificationTemplate
     * @throws \Exception
     */
    public static function getByName($name)
    {
        //TODO caching
        $template = self::where('name', '=', $name)->first();
        if (!$template) throw new \Exception('Notification \''.$name.'\' not exist');

        return $template;
    }

    public static function getByEvent($event)
    {
        $event = str_replace('\\', '\\\\\\', $event);
        $items = self::where('events', 'like', '%'.$event.'%')->get();
        $event = str_replace('\\\\\\', '\\', $event);

        $items = $items->filter(function ($v, $k) use ($event) {
            return array_search($event, explode('@', $v->events)) !== false;
        });

        return $items;
    }

    public function fillTextPlaceholders($notifiable, $payload = null)
    {
        if ($notifiable->email === null) {
            $data = static::flattenPayload($payload);

            $placeholders = [
                '{user.name}' => $notifiable->name,
                '{user.full_name}' => $notifiable->full_name,
                '{user.first_name}' => $notifiable->first_name,
                '{user.last_name}' => $notifiable->last_name,
                '{user.middle_name}' => $notifiable->middle_name,
                '{user.animals.count}' => $notifiable->animals->count(),
                '{user.animals_verified.count}' => $notifiable->animalsVerified()->count(),
                '{user.animals_unverified.count}' => $notifiable->animalsUnverified()->count(),
                '{animal.nickname}' => array_key_exists('nickname', $data) ? $data['nickname'] : '',
                '{animal.badge_num}' => array_key_exists('badge', $data) ? $data['badge'] : '',
            ];

            return str_replace(array_keys($placeholders), array_values($placeholders), $this->body);
        }
        return $this->body;
    }

    public static function flattenPayload($payload)
    {
        $res = [];
        if (is_array($payload)) {
            foreach ($payload as $k => $item) {
                if (!$item) continue;

                if (is_array($item)) {
                    if (is_string($k)) {
                        $res[$k] = $item;
                    } else {
                        $res = array_merge($res, $item);
                    }
                } else if ($item instanceof Model) {
                    $res = array_merge($res, $item->toArray());
                } else {
                    $res[$k] = $item;
                }
            }
        }
        return $res;
    }
}
