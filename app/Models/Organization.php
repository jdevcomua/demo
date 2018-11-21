<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    protected $fillable = ['id', 'name', 'chief_full_name', 'contact_info', 'address', 'requisites'];

    public function files()
    {
        return $this->hasMany(OrganizationsFile::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

}
