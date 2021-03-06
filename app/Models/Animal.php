<?php

namespace App\Models;

use App\Helpers\Date;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property string $nickname
 * @property int $species_id
 * @property int $breed_id
 * @property int $color_id
 * @property int $fur_id
 * @property int $gender
 * @property \Illuminate\Support\Carbon $birthday
 * @property int $sterilized
 * @property int|null $user_id
 * @property int $verified
 * @property string|null $comment
 * @property string|null $number
 * @property int|null $confirm_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $badge
 * @property int|null $request_user_id
 * @property int|null $archived_id
 * @property string|null $archived_type
 * @property string|null $archived_at
 * @property string|null $chip
 * @property string|null $clip
 * @property int|null $tallness
 * @property string|null $nickname_lat
 * @property int|null $veterinary_passport_id
 * @property string|null $testing
 * @property int $half_breed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalOffense[] $animalOffenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalVeterinaryMeasure[] $animalVeterinaryMeasure
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $archivable
 * @property-read \App\Models\Breed $breed
 * @property-read \App\Models\ChangeAnimalOwner $changeOwner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalChronicle[] $chronicles
 * @property-read \App\Models\Color $color
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $files
 * @property-read \App\Models\Fur $fur
 * @property-read mixed $age
 * @property-read mixed $documents
 * @property-read mixed $identifying_devices_count
 * @property-read mixed $images
 * @property-read mixed $verification
 * @property-read mixed $veterinary_measures
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $history
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IdentifyingDevice[] $identifyingDevices
 * @property-read \App\Models\LostAnimal $lost
 * @property-read \App\Models\Species $species
 * @property-read \App\Models\Sterilization $sterilization
 * @property-read \App\User|null $user
 * @property-read \App\User|null $userThatRequest
 * @property-read \App\Models\Vaccination $vaccination
 * @property-read \App\Models\VeterinaryPassport|null $veterinaryPassport
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal query()
 * @mixin \Eloquent
 */
class Animal extends Model
{

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    const IDENTIFYING_DEVICES_TYPE_CHIP = 1;
    const IDENTIFYING_DEVICES_TYPE_CLIP = 2;
    const IDENTIFYING_DEVICES_TYPE_BADGE = 3;
    const IDENTIFYING_DEVICES_TYPE_BRAND = 4;

    private $identifying_devices = [
        'chip' => 'Чіп',
        'clip' => 'Кліпса',
        'badge' => 'Жетон з QR-кодом'
    ];

    protected $fillable = [
        'id', 'nickname', 'species_id', 'gender', 'breed_id', 'color_id', 'fur_id', 'user_id',
        'birthday', 'sterilized', 'comment', 'verified', 'number', 'badge', 'request_user_id',
        'archived_type', 'archived_at', 'clip', 'chip', 'tallness', 'nickname_lat', 'testing',
        'half_breed',

        //generated attributes, don't fill them
        '_verification',
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];


    public function delete()
    {
        $files = $this->files;
        if ($files) {
            foreach ($files as $file) \Storage::delete($file->attributes['path']);
        }
        return parent::delete();
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

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

    public function fur()
    {
        return $this->belongsTo('App\Models\Fur');
    }

    public function files()
    {
        return $this->hasMany('App\Models\AnimalsFile');
    }

    public function chronicles()
    {
        return $this->hasMany(AnimalChronicle::class);
    }

    public function getImagesAttribute()
    {
        return $this->files
            ->where('type', '=', AnimalsFile::FILE_TYPE_PHOTO);
    }

    public function getDocumentsAttribute()
    {
        return $this->files
            ->where('type', '=', AnimalsFile::FILE_TYPE_DOCUMENT);
    }

    public function history()
    {
        return $this->morphMany('App\Models\Log', 'object');
    }

    public function getVerificationAttribute()
    {
        if(!array_key_exists('_verification', $this->attributes)) {
            $this->attributes['_verification'] = $this->history
                ->where('action', '=', Log::ACTION_VERIFY)
                ->sortByDesc('id')
                ->first();
        }
        return $this->attributes['_verification'];
    }

    public function userThatRequest()
    {
        return $this->belongsTo(User::class, 'request_user_id');
    }

    public function lost()
    {
        return $this->hasOne(LostAnimal::class);
    }

    public function changeOwner()
    {
        return $this->hasOne(ChangeAnimalOwner::class);
    }

    public function sterilization()
    {
        return $this->hasOne(Sterilization::class);
    }

    public function vaccination()
    {
        return $this->hasOne(Vaccination::class);
    }

    public function animalVeterinaryMeasure()
    {
        return $this->hasMany(AnimalVeterinaryMeasure::class);
    }

    public function animalOffenses()
    {
        return $this->hasMany(AnimalOffense::class);
    }

    public function archivable()
    {
        return $this->morphTo('archived', 'archived_type', 'archived_id');
    }

    public function veterinaryPassport()
    {
        return $this->belongsTo(VeterinaryPassport::class);
    }

    public function identifyingDevices()
    {
        return $this->hasMany(IdentifyingDevice::class);
    }

    public function getBadgeAttribute()
    {
        $badge = $this->identifyingDevices()
            ->where('identifying_device_type_id', '=', IdentifyingDeviceType::TYPE_BADGE)
            ->first();
        return $badge;
    }

    public function identifyingDevicesArray(): array
    {
        return $this->identifying_devices;
    }

    public function getIdentifyingDevicesCountAttribute(): int
    {
        $count = 0;

        foreach ($this->identifying_devices as $k => $v) {
            if ($this->$k !== null) {
                $count++;
            }
        }

        return $count;
    }

    public function getAgeAttribute()
    {
        $diff = $this->birthday->diff(Carbon::now());
        return Date::getDiffLocalized($diff);
    }

    public function hasDocuments(): bool
    {
        return $this->hasFilesCommonLogic($this, 'documents');
    }

    public function hasVetFiles(): bool
    {
        return $this->hasFilesCommonLogic($this->animalVeterinaryMeasure, 'files', 1);
    }

    private function hasFilesCommonLogic($entity, $filesRelationName, $iterable = null): bool
    {
        $hasFiles = false;
        if ($iterable !== null) {
            foreach ($entity as $entityItem) {
                if (count($entityItem->$filesRelationName)) {
                    return true;
                }
            }
        } else {
            return count($entity->$filesRelationName);
        }

        return $hasFiles;
    }

    public function getAvailableIdentifyingDevicesTypes()
    {
        $allDeviceTypes = IdentifyingDeviceType::all();

        if ($this->identifyingDevices !== null && count($this->identifyingDevices)) {
            $takenDeviceTypes = [];
            foreach ($this->identifyingDevices as $identifyingDevice) {
                $takenDeviceTypes[] = $identifyingDevice->type;
            }
            $takenDeviceTypesCollection = collect($takenDeviceTypes);
            return $allDeviceTypes->diff($takenDeviceTypesCollection);
        }
        return $allDeviceTypes;
    }


    public function getVeterinaryMeasuresAttribute()
    {
        $veterinaryMeasures = $this->animalVeterinaryMeasure;

        if ($this->sterilization !== null) {
            $veterinaryMeasures[] = $this->sterilization;
        }

        if ($this->vaccination !== null) {
            $veterinaryMeasures[] = $this->vaccination;
        }

        return $veterinaryMeasures;
    }

}
