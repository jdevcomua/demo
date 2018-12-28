<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailLog
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $receiver
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUserId($value)
 */
class EmailLog extends Model
{
    protected $fillable =
        [
            'user_id',
            'text'
        ];

    public function receiver ()
    {
        return $this->belongsTo(User::class);
    }
}
