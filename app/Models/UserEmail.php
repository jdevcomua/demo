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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEmail whereUserId($value)
 * @mixin \Eloquent
 */
class UserEmail extends Model
{
    protected $fillable = [
        'type', 'email',
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
