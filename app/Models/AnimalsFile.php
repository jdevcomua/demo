<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalsFile
 *
 * @property int $id
 * @property int $animal_id
 * @property int $type
 * @property string $path
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AnimalsFile extends Model
{
    protected $fillable = [
        'path'
    ];

    public static $FILE_TYPE_PHOTO = 0;
    public static $FILE_TYPE_DOCUMENT = 1;


    public function animal()
    {
        return $this->belongsTo('App\Models\Animal');
    }

    public function getFileNameAttribute()
    {
        $temp = explode('/', $this->path);
        $count = count($temp);
        $filename = $temp[$count -1];
        return $filename;
    }
}
