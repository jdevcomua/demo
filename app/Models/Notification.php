<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $type
 * @property string $min
 * @property string $max
 * @property string $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Model
{

    const TYPE_NOT_VERIFIED = 'неверифіковані тварини';

    protected $fillable = [
        'id', 'min', 'max', 'type', 'text',
    ];
}
