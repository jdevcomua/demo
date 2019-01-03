<?php

namespace App\Services\Pdf\DataProviders;


use App\Models\Animal;
use App\Models\Species;
use App\Services\Pdf\Contracts\PdfDataProviderInterface;

class ReportAnimalsByBreedsPdfDataProvider extends CommonLogicPdfDataProvider implements PdfDataProviderInterface
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