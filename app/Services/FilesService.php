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
            foreach ($data['images'] as $num => $image) {
                $this->storeAnimalFile($animal, $image, $num, AnimalsFile::FILE_TYPE_PHOTO);
            }
        }
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeAnimalFile($animal, $document, null, AnimalsFile::FILE_TYPE_DOCUMENT);
            }
        }
    }

    private function storeAnimalFile($animal, $file, $num, $type)
    {
        $fileName = self::sanitaze_name(substr($file->getClientOriginalName(),0,100));

        $data = [
            'animal_id' => $animal->id,
            'type' => $type,
        ];

        if ($type === AnimalsFile::FILE_TYPE_PHOTO) {
            $data['path'] = $file->storeAs('animals/' . $animal->id
                . AnimalsFile::FILE_TYPE_PHOTO_FOLDER, $fileName);
            $data['num'] = $num;

            $file = AnimalsFile::where([
                'animal_id' => $animal->id,
                'num' => $num
            ])->first();

            if ($file) {
                \Storage::delete($file->getOriginal('path'));
                $file->update($data);
                return;
            }
        }

        if ($type === AnimalsFile::FILE_TYPE_DOCUMENT) {
            $data['path'] = $file->storeAs('animals/' . $animal->id
                . AnimalsFile::FILE_TYPE_DOCUMENT_FOLDER, $fileName);
        }

        $animal->files()->create($data);
    }

    private function sanitaze_name($name)
    {
        $indexOff = strrpos($name, '.');
        $nameFile = substr($name,0, $indexOff);
        $extension = substr($name, $indexOff);
        $clean = preg_replace("([^\w\s\d\-_~,;\[\]\(\)])", "", $nameFile);
        $nameOut = str_replace(' ', '', $clean) . '_' . str_random(2) . $extension;
        return $nameOut;
    }

}