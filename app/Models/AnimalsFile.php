<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalsFile
 *
 * @property int $id
 * @property int $animal_id
 * @property int $type
 * @property int|null $num
 * @property string $path
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AnimalsFile extends Model
{

    const STORAGE_PREFIX = 'storage/';

    protected $fillable = [
        'path', 'type', 'num'
    ];

    const FILE_TYPE_PHOTO = 0;
    const FILE_TYPE_DOCUMENT = 1;

    const FILE_TYPE_PHOTO_FOLDER = '/images';
    const FILE_TYPE_DOCUMENT_FOLDER = '/documents';


    public function delete()
    {
        \Storage::delete($this->attributes['path']);
        return parent::delete();
    }


    public function animal()
    {
        return $this->belongsTo('App\Models\Animal');
    }

    public function getPathAttribute()
    {
        return self::STORAGE_PREFIX . $this->attributes['path'];
    }

    public function getNameAttribute()
    {
        $arr = explode('/', $this->attributes['path']);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        array_pop($arr);
        return implode('.', $arr);
    }

    public function getExtensionAttribute()
    {
        $arr = explode('/', $this->attributes['path']);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        if (count($arr) === 1) return '';
        return array_pop($arr);
    }
}
