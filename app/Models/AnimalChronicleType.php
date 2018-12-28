<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
