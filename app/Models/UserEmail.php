<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserEmail
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail query()
 * @mixin \Eloquent
 */
class UserEmail extends Model
{
    const TYPE_PRIMARY = 'PRIMARY';
    const TYPE_ADDITIONAL = 'ADDITIONAL';
    const TYPE_MANUAL = 'MANUAL';

    protected $fillable = [
        'type', 'email',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
