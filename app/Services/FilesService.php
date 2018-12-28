<?php

namespace App\Services;

use App\Models\AnimalsFile;
use App\Models\FoundAnimalsFile;
use App\Models\OrganizationsFile;

class FilesService
{

    /**
     * @param \Illuminate\Database\Eloquent\Model|\App\Models\Animal $animal
     * @param array $data
     */
    public function handleAnimalFilesUpload($animal, $data)
    {
        if (array_key_exists('images', $data)) {
            if (array_key_exists(0, $data['images'])) {
                $imgCurr = 0;
                $skip = $this->getUsedImagesNum($animal);
                for ($i = 1; $i <= AnimalsFile::MAX_PHOTO_COUNT; $i++) {
                    if (array_key_exists($i, $skip)) continue;
                    $this->storeAnimalFile($animal, $data['images'][$imgCurr], $i, AnimalsFile::FILE_TYPE_PHOTO);
                    $imgCurr++;
                    if ($imgCurr >= count($data['images'])) break;
                }
            } else {
                foreach ($data['images'] as $num => $image) {
                    $this->storeAnimalFile($animal, $image, $num, AnimalsFile::FILE_TYPE_PHOTO);
                }
            }
        }
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeAnimalFile($animal, $document, null, AnimalsFile::FILE_TYPE_DOCUMENT);
            }
        }
    }

    public function handleOrganizationFilesUpload($organization, $data)
    {
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeOrganizationFile($organization, $document);
            }
        }
    }

    public function handleVeterinaryMeasureFilesUpload($animalVeterinaryMeasure, $data)
    {
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeVeterinaryMeasureFile($animalVeterinaryMeasure, $document);
            }
        }
    }

    public function handleAnimalOffenseFilesUpload($animalOffense, $data)
    {
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeAnimalOffenseFile($animalOffense, $document);
            }
        }
    }

    public function handleFoundAnimalFilesUpload($foundAnimal, $data)
    {
        if (array_key_exists('documents', $data)) {
            foreach ($data['documents'] as $document) {
                $this->storeFoundAnimalFile($foundAnimal, $document);
            }
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|\App\Models\Animal $animal
     */
    private function getUsedImagesNum($animal)
    {
        return $animal->images->pluck('id', 'num')->toArray();
    }

    private function storeAnimalFile($animal, $file, $num, $type)
    {
        $fileName = self::sanitaze_name($file->getClientOriginalName());

        $data = [
            'animal_id' => $animal->id,
            'type' => $type,
            'name' => $fileName
        ];

        if ($type === AnimalsFile::FILE_TYPE_PHOTO) {
            $data['path'] = $file->store('animals/' . $animal->id
                . AnimalsFile::FILE_TYPE_PHOTO_FOLDER);
            $data['num'] = $num;

            $file = AnimalsFile::where([
                'animal_id' => $animal->id,
                'num' => $num,
            ])->first();

            if ($file) {
                \Storage::delete($file->getOriginal('path'));
                $file->update($data);
                return;
            }
        }

        if ($type === AnimalsFile::FILE_TYPE_DOCUMENT) {
            $data['path'] = $file->store('animals/' . $animal->id
                . AnimalsFile::FILE_TYPE_DOCUMENT_FOLDER);
        }

        $animal->files()->create($data);
    }

    private function storeOrganizationFile($organization, $file)
    {
        $fileName = self::sanitaze_name($file->getClientOriginalName());

        $data = [
            'organization_id' => $organization->id,
            'name' => $fileName
        ];

            $data['path'] = $file->store('organizations/' . $organization->id
                . OrganizationsFile::FILE_DOCUMENT_FOLDER);

        $organization->files()->create($data);
    }

    private function storeVeterinaryMeasureFile($animalVeterinaryMeasure, $file)
    {
        $fileName = self::sanitaze_name($file->getClientOriginalName());

        $data = [
            'animal_veterinary_measure_id' => $animalVeterinaryMeasure->id,
            'name' => $fileName
        ];

        $data['path'] = $file->store('veterinary_measures/' . $animalVeterinaryMeasure->id
            . OrganizationsFile::FILE_DOCUMENT_FOLDER);

        $animalVeterinaryMeasure->files()->create($data);
    }

    private function storeAnimalOffenseFile($animalOffense, $file)
    {
        $fileName = self::sanitaze_name($file->getClientOriginalName());

        $data = [
            'animal_offense_id' => $animalOffense->id,
            'name' => $fileName
        ];

        $data['path'] = $file->store('animal_offenses/' . $animalOffense->id
            . OrganizationsFile::FILE_DOCUMENT_FOLDER);

        $animalOffense->files()->create($data);
    }

    private function storeFoundAnimalFile($foundAnimal, $file)
    {
        $fileName = self::sanitaze_name($file->getClientOriginalName());

        $data = [
            'found_animal_id' => $foundAnimal->id,
            'name' => $fileName
        ];

        $data['path'] = $file->store('found_animals/' . $foundAnimal->id
            . FoundAnimalsFile::FILE_IMAGE_FOLDER);

        $foundAnimal->images()->create($data);
    }

    private function sanitaze_name($name)
    {
        $indexOff = strrpos($name, '.');
        $nameFile = substr($name,0, $indexOff);
        $extension = substr($name, $indexOff);
        $clean = preg_replace("([^\w\d\-_\(\)]+/u)", "", $nameFile);
        $clean = substr($clean, 0, 40);
        return $clean . $extension;
    }

}
