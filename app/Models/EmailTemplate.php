<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailTemplate
 *
 * @property int $id
 * @property string $subject
 * @property string $name
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailTemplate query()
 * @mixin \Eloquent
 */
class EmailTemplate extends Model
{
    protected $fillable = ['id','subject', 'name', 'body'];

}
