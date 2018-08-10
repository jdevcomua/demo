<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    const TYPE_NOT_VERIFIED = 'неверифіковані тварини';

    protected $fillable = [
        'id', 'min', 'max', 'type', 'text',
    ];
}
