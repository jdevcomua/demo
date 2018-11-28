<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class FoundAnimal extends Model
{
    use Sortable;

    protected $fillable = [
        'id', 'species_id', 'breed_id',
        'color_id', 'badge', 'found_address', 'contact_name',
        'contact_phone', 'contact_email'
    ];

    public function breed()
    {
        return $this->belongsTo('App\Models\Breed');
    }

    public function species()
    {
        return $this->belongsTo('App\Models\Species');
    }

    public function color()
    {
        return $this->belongsTo('App\Models\Color');
    }

    public function images()
    {
        return $this->hasMany(FoundAnimalsFile::class);
    }

    public function getContactInfoAttribute(): String
    {
        $contactInfo = [
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email
        ];

        return json_encode($contactInfo);
    }


}
