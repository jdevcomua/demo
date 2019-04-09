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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotificationTemplate query()
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
        $data = static::flattenPayload($payload);
        $placeholders = [];

        if (get_class($notifiable) === 'App\User') {
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
        }

        if (get_class($notifiable) === 'App\Models\FoundAnimal') {
            $placeholders = [
                '{found_animal.species}' => $notifiable->species ? $notifiable->species->name : null,
                '{found_animal.breed}' => $notifiable->breed ? $notifiable->breed->name : null,
                '{found_animal.color}' => $notifiable->color ? $notifiable->color->name : null,
                '{found_animal.badge}' => $notifiable->badge,
                '{found_animal.found_address}' => $notifiable->found_address,
                '{found_animal.contact_name}' => $notifiable->contact_name,
                '{found_animal.contact_phone}' => $notifiable->contact_phone,
                '{found_animal.contact_email}' => $notifiable->contact_email,
                '{found_animal.additional_info}' => $notifiable->additional_info,
            ];
        }
        return count($placeholders)
            ? str_replace(array_keys($placeholders), array_values($placeholders), $this->body)
            : $this->body;
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
