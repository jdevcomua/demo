<?php


namespace App\Services\Animals;



use App\Models\Animal;

interface AnimalChronicleServiceInterface
{
    /*
     * Creates new animal chronicle record
     */
    public function addAnimalChronicle(Animal $animal, string $chronicle_type, array $field_values = null);

}