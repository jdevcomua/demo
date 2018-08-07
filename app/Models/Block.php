<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Block
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Block whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Block extends Model
{
    protected $fillable = ['title', 'subject','body'];
}
