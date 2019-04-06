<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalVeterinaryMeasureFile
 *
 * @property int $id
 * @property int $animal_veterinary_measure_id
 * @property string $name
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AnimalVeterinaryMeasure $animalVeterinaryMeasure
 * @property-read mixed $file_extension
 * @property-read mixed $file_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasureFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasureFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalVeterinaryMeasureFile query()
 * @mixin \Eloquent
 */
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
        //todo этот метод повторяется в нескольких моделях вынеси в трейт или в какую-то базовую модель
        $arr = explode('/', $this->name);
        $fname = $arr[count($arr) - 1];
        $arr = explode('.', $fname);
        array_pop($arr);
        return implode('.', $arr);
    }

    public function getFileExtensionAttribute()
    {
        //todo этот метод повторяется в нескольких моделях вынеси в трейт или в какую-то базовую модель
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
