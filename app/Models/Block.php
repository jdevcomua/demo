<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Block
 *
 * @property int $id
 * @property string $title
 * @property string|null $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block query()
 * @mixin \Eloquent
 */
class Block extends Model
{
    protected $fillable = [
        'id', 'title', 'subject', 'body'
    ];
}
