<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalVeterinaryMeasureFile extends Model
{
    protected $fillable = ['animal_veterinary_measure_id', 'path', 'name'];

    private $img_extensions = ['jpg', 'png', 'jpeg', 'bmp', 'svg'];

    public function isImage(): bool
    {
        return in_array($this->file_extension, $this->img_extensions);
    }

    public function getPathAttribute()
    {
        return 'storage/' . $this->attributes['path'];
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

    public function animalVeterinaryMeasure()
    {
        return $this->belongsTo(AnimalVeterinaryMeasure::class);
    }
}
