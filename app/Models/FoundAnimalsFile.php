<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function foundAnimal()
    {
        return $this->belongsTo(FoundAnimal::class);
    }
}
