<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MailingConfig
 *
 * @property int $id
 * @property int|null $group_id
 * @property int|null $email_template_id
 * @property string $type
 * @property int|null $period_type
 * @property int|null $period
 * @property string|null $dates
 * @property int $is_active
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $last_fired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EmailTemplate|null $emailTemplate
 * @property-read \App\Models\Group|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailingConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailingConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MailingConfig query()
 * @mixin \Eloquent
 */
class MailingConfig extends Model
{
    protected $fillable = ['id',
        'email_template_id',
        'type',
        'period_type',
        'period',
        'dates',
        'is_active',
        'priority',
        'last_fired',
        'updated_at',
        'created_at'];

    protected $dates = ['last_fired', 'updated_at', 'created_at'];

    const TYPE_SCHEDULED = 'scheduled';
    const TYPE_DATES = 'dates';

    const PERIOD_DAYS = 1;
    const PERIOD_WEEKS = 2;
    const PERIOD_MONTH = 3;

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public static function getMailingTypes()
    {
        return [
            self::TYPE_SCHEDULED => 'Scheduled',
            self::TYPE_DATES => 'Dates'
        ];
    }
    public static function getPeriodTypes()
    {
        return [
            self::PERIOD_DAYS => 'Days',
            self::PERIOD_WEEKS => 'Weeks',
            self::PERIOD_MONTH => 'Month'
        ];
    }

    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High'
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public static function getEvents()
    {
        return [
            'test' => 'Test event'
        ];
    }
}
