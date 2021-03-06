<?php


namespace App\Services\Animals;


use App\Models\Animal;
use App\Models\AnimalChronicle;

class AnimalChronicleService implements AnimalChronicleServiceInterface
{

    public function addAnimalChronicle(Animal $animal, string $chronicle_type, array $field_values = null)
    {
        $chronicle = new AnimalChronicle;
        $chronicle->animal_id = $animal->id;
        $chronicle->type = $chronicle_type;
        $chronicle->save();

        //TODO: validation for that field value exist for certain chronicle type in a new animal chronicle entry
        if ($field_values !== null) {
            $chronicle->fields = $field_values;
        }

    }
}