<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization
 *
 * @property int $id
 * @property string $name
 * @property string $chief_full_name
 * @property string $contact_info
 * @property string $address
 * @property string $requisites
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrganizationsFile[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $history
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization query()
 * @mixin \Eloquent
 */
class Organization extends Model
{

    protected $fillable = ['id', 'name', 'chief_full_name', 'contact_info', 'address', 'requisites'];

    public function files()
    {
        return $this->hasMany(OrganizationsFile::class);
    }

    public function history()
    {
        return $this->morphMany('App\Models\Log', 'object');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

}
