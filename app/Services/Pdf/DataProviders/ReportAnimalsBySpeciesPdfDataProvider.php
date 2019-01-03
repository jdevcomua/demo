<?php

namespace App\Services\Pdf\DataProviders;


use App\Models\Animal;
use App\Models\Species;
use App\Services\Pdf\Contracts\PdfDataProviderInterface;

class ReportAnimalsBySpeciesPdfDataProvider extends CommonLogicPdfDataProvider implements PdfDataProviderInterface
{
    public function data(): Document
    {
        $registeredAnimalsTable = new Table;
        $registeredAnimalsTable
            ->setHeaders(['№ з/п', 'Вид', 'Кількість'])
            ->setColumns($this->animalsBySpeciesColumns());

        $document = new Document;
        $document
            ->setTitle('Звіт про кількість тварин в системі РДТ за видом')
            ->setTables([$registeredAnimalsTable]);

        return $document;
    }

    private function animalsBySpeciesColumns()
    {
        $dogSpeciesId = Species::where('name', '=', 'Собака')->first()->id;
        $catSpeciesId = Species::where('name', '=', 'Кiт')->first()->id;

        $dogsAmount = Animal::where([
            ['species_id', '=', $dogSpeciesId],
        ])->get()->count();

        $catsAmount = Animal::where([
            ['species_id', '=', $catSpeciesId],
        ])->get()->count();

        $this->resetRowNumber();
        return [
            [$this->rowNumber(), 'Собаки', $dogsAmount],
            [$this->rowNumber(), 'Коти', $catsAmount],
            ];
    }

}