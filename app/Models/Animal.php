<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property int $species_id
 * @property int $breed_id
 * @property \Carbon\Carbon|null $birthday
 * @property int $gender
 * @property int $color_id
 * @property bool $sterilized
 * @property string $nickname
 * @property int $user_id
 * @property int $verified
 * @property string|null $comment
 * @property array $data
 * @property string|null $number
 * @property int|null $confirm_user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Breed $breed
 * @property-read \App\Models\Color $color
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnimalsFile[] $images
 * @property-read \App\Models\Species $species
 * @property-read \App\User $user
 * @property-read \App\User|null $userThatConfirmed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereConfirmUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Animal whereData($value)
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
 */
class Animal extends Model
{

    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    protected $fillable = [
        'date_of_birth', 'gender', 'sterilized', 'nickname',
        'verified', 'data', 'number', 'comment',
        'nickname', 'species_id', 'gender', 'breed_id', 'color_id', 'birthday',
        'sterilized', 'comment', 'verified', 'data', 'number',
    ];

    protected $dates = [
        'birthday', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'gender' => 'integer',
        'sterilized' => 'boolean',
        'status' => 'integer',
        'data' => 'array',
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

    public function userThatConfirmed()
    {
        return $this->belongsTo('App\User', 'confirm_user_id');
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

    public function files()
    {
        return $this->hasMany('App\Models\AnimalsFile');
    }

    public function images()
    {
        return $this->hasMany('App\Models\AnimalsFile')
            ->where('type', '=', AnimalsFile::FILE_TYPE_PHOTO);
    }

    public function documents()
    {
        return $this->hasMany('App\Models\AnimalsFile')
            ->where('type', '=', AnimalsFile::FILE_TYPE_DOCUMENT);
    }
}
