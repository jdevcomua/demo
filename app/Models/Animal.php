<?php

namespace App\Models;

use App\User;
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
 * @property \Carbon\Carbon $birthday
 * @property int $sterilized
 * @property int $user_id
 * @property int $verified
 * @property string|null $comment
 * @property string|null $number
 * @property string|null $badge
 * @property int|null $confirm_user_id
 * @property int|null $request_user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Breed $breed
 * @property-read \App\Models\Color $color
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $files
 * @property-read \App\Models\Fur $fur
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $images
 * @property-read \App\Models\Species $species
 * @property-read \App\User $user
 * @property-read \App\User|null $userThatConfirmed
 * @property-read \App\User|null $userThatRequest
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereConfirmUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereFurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereSpeciesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereSterilized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereVerified($value)
 * @mixin \Eloquent
 * @property-read mixed $verification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $history
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereRequestUserId($value)
 */
class Animal extends Model
{

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    protected $fillable = [
        'id', 'nickname', 'species_id', 'gender', 'breed_id', 'color_id', 'fur_id', 'user_id',
        'birthday', 'sterilized', 'comment', 'verified', 'number', 'badge', 'request_user_id',

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

    public function userThatRequest () {
        return $this->belongsTo(User::class, 'request_user_id');
    }

    public function lostRecord(): ?LostAnimals
    {
        return LostAnimals::where(['found' => LostAnimals::FOUND_NO])->first();
    }

    public function isLost(): bool
    {
        return ($this->lostRecord()) ?  !(bool)LostAnimals::where(['animal_id' => $this->id])->first()->found : false;
    }
}
