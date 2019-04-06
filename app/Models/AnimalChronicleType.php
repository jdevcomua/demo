<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnimalChronicleType
 *
 * @property int $id
 * @property string $type
 * @property string $template_text
 * @property-read \App\Models\AnimalChronicle $chronicle
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalChronicleField[] $fields
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnimalChronicleType query()
 * @mixin \Eloquent
 */
class AnimalChronicleType extends Model
{
    protected $fillable = [ 'type', 'template_text' ];

    public $timestamps = false;

    public function fields()
    {
        return $this->hasMany(AnimalChronicleField::class);
    }

    public function chronicle()
    {
        return $this->hasOne(AnimalChronicle::class);
    }

}
