<?php

namespace App\Models;

use App\Helpers\ProcessedCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\FoundAnimal
 *
 * @property int $id
 * @property int|null $species_id
 * @property int|null $breed_id
 * @property int|null $color_id
 * @property string|null $badge
 * @property string|null $found_address
 * @property string $contact_name
 * @property string $contact_phone
 * @property string|null $contact_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $approved
 * @property int $processed
 * @property string|null $additional_info
 * @property-read \App\Models\Breed|null $breed
 * @property-read \App\Models\Color|null $color
 * @property-read mixed $contact_info
 * @property-read mixed $email
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FoundAnimalsFile[] $images
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Species|null $species
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FoundAnimal sortable($defaultParameters = null)
 * @mixin \Eloquent
 */
class FoundAnimal extends Model
{
    use Notifiable, Sortable, ProcessedCache;

    protected $fillable = [
        'id', 'species_id', 'breed_id',
        'color_id', 'badge', 'found_address', 'contact_name',
        'contact_phone', 'contact_email', 'processed', 'approved'
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
        return $this->hasMany(FoundAnimalsFile::class);    //Todo выбери один тип обозначения класса, или строкой или вызывай метод ::class
    }

    public function getEmailAttribute()
    {
        return $this->contact_email;
    }

    public function getContactInfoAttribute(): String
    {
        $contactEmail = $this->contact_email ?? 'Не заповнено';
        $contactInfo = [
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $contactEmail
        ];

        return json_encode($contactInfo);
    }

}
