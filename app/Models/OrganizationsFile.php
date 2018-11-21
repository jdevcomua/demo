<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationsFile extends Model
{
    protected $fillable = ['path', 'name'];

    const STORAGE_PREFIX = 'storage/';

    const FILE_DOCUMENT_FOLDER = '/documents';

    private $img_extensions = ['jpg', 'png', 'jpeg', 'bmp', 'svg'];

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

    public function isImage(): bool
    {
        return in_array($this->file_extension, $this->img_extensions);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
