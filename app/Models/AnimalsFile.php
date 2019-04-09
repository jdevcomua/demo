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
 * @property string $name
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read mixed $file_extension
 * @property-read mixed $file_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalsFile query()
 * @mixin \Eloquent
 */
class AnimalsFile extends Model
{

    const STORAGE_PREFIX = 'storage/';

    protected $fillable = [
        'name', 'path', 'type', 'num'
    ];

    const MAX_PHOTO_COUNT = 9;

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

    public function getFileNameAttribute()
    {
        $arr = explode('/', $this->name);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        array_pop($arr);
        return implode('.', $arr);
    }

    public function getFileExtensionAttribute()
    {
        $arr = explode('/', $this->name);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        if (count($arr) === 1) return '';
        return array_pop($arr);
    }
}
