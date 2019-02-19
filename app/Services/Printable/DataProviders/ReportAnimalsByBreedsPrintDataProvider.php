<?php

namespace App\Services\Printable\DataProviders;


use App\Models\Species;
use App\Services\Printable\Contracts\PrintDataProviderInterface;

class ReportAnimalsByBreedsPrintDataProvider extends CommonLogicPrintDataProvider implements PrintDataProviderInterface
{
    public function data(): Document
    {
        $registeredAnimalsTable = new Table;
        $registeredAnimalsTable
            ->setHeaders(['№ з/п', 'Вид', 'Порода', 'Кількість'])
            ->setColumns($this->animalsByBreedsColumns());

        $document = new Document;
        $document
            ->setTitle('Звіт про кількість тварин в системі РДТ за породою')
            ->enableSignBlock()
            ->setTables([$registeredAnimalsTable]);

        return $document;
    }

    private function animalsByBreedsColumns()
    {
        $animalsByBreeds = [];

        $species = Species::all();

        $this->resetRowNumber();

        foreach ($species as $speciesSingle) {
            if ($speciesSingle !== null) {
                foreach ($speciesSingle->breeds as $breed) {
                    $animalsAmount = $breed->animals->count();
                    $animalsByBreeds[] = [$this->rowNumber(), $speciesSingle->name, $breed->name, $animalsAmount];
                }
            }
        }

        return $animalsByBreeds;
    }

}