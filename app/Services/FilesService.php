<?php

namespace App\Services;

use App\Models\AnimalsFile;

class FilesService
{

    /**
     * @param \Illuminate\Database\Eloquent\Model|\App\Models\Animal $animal
     * @param array $data
     */
    public function handleAnimalFilesUpload($animal, $data)
    {
        if (array_key_exists('images', $data)) {
            foreach ($data['images'] as $image) {
                $animal->files()->create([
                    'animal_id' => $animal->id,
                    'type' => AnimalsFile::FILE_TYPE_PHOTO,
                    'path' => $image->store('animals/'.$animal->id.'/images')
                ]);
            }
        }
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $animal->files()->create([
                    'animal_id' => $animal->id,
                    'type' => AnimalsFile::FILE_TYPE_DOCUMENT,
                    'path' => $image->store('animals/'.$animal->id.'/documents')
                ]);
            }
        }
    }

}