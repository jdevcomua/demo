<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPhone
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPhone query()
 * @mixin \Eloquent
 */
class UserPhone extends Model
{

    const TYPE_PRIMARY = 'PRIMARY';
    const TYPE_ADDITIONAL = 'ADDITIONAL';
    const TYPE_MANUAL = 'MANUAL';

    protected $fillable = [
        'type', 'phone',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
