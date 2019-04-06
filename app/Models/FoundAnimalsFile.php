<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FoundAnimalsFile
 *
 * @property int $id
 * @property int $found_animal_id
 * @property string $name
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FoundAnimal $foundAnimal
 * @property-read mixed $file_extension
 * @property-read mixed $file_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimalsFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimalsFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimalsFile query()
 * @mixin \Eloquent
 */
class FoundAnimalsFile extends Model
{
    protected $fillable = ['path', 'name'];

    const STORAGE_PREFIX = 'storage/';

    const FILE_IMAGE_FOLDER = '/images';

    public function delete()
    {
        \Storage::delete($this->attributes['path']);
        return parent::delete();
    }

    public function getPathAttribute()
    {
        return self::STORAGE_PREFIX . $this->attributes['path'];
    }

    public function getFileNameAttribute()
    {
        //todo дубликат метода
        $arr = explode('/', $this->name);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        array_pop($arr);
        return implode('.', $arr);
    }

    public function getFileExtensionAttribute()
    {
        //todo дубликат метода
        $arr = explode('/', $this->name);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        if (count($arr) === 1) return '';
        return array_pop($arr);
    }

    public function foundAnimal()
    {
        return $this->belongsTo(FoundAnimal::class);
    }
}
