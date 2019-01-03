<?php

namespace App\Services\Pdf\DataProviders;


use App\Models\Animal;
use App\Models\Species;
use App\Services\Pdf\Contracts\PdfDataProviderInterface;

class ReportRegisteredHomelessAnimalsPdfDataProvider extends CommonLogicPdfDataProvider implements PdfDataProviderInterface
{
    private $dateFrom;
    private $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function data(): Document
    {
        $registeredHomelessAnimalsTable = new Table;
        $registeredHomelessAnimalsTable
            ->setTitle($this->makeTitle())
            ->setHeaders(['№ з/п', 'Тварини', 'Всього'])
            ->setColumns($this->registeredAnimalsHomelessColumns());

        $document = new Document;
        $document
            ->setTitle('Звіт про реєстрацію безпритульних тварин в системі РДТ за період')
            ->setTables([$registeredHomelessAnimalsTable]);

        return $document;
    }

    private function makeTitle()
    {
        return 'Звіт про реєстрацію безпритульних тварин в системі РДТ за період з '
            . $this->localizedDate($this->dateFrom)
            . 'р. по '
            . $this->localizedDate($this->dateTo)
            . 'р.';
    }

    private function registeredAnimalsHomelessColumns()
    {
        $dogSpeciesId = Species::where('name', '=', 'Собака')->first()->id;
        $catSpeciesId = Species::where('name', '=', 'Кiт')->first()->id;

        $dogsAmount = static::whereInDateRange($this->dateFrom, $this->dateTo)->where([
            ['user_id', '=', null],
            ['species_id', '=', $dogSpeciesId],
        ])->get()->count();


        $catsAmount = static::whereInDateRange($this->dateFrom, $this->dateTo)->where([
            ['user_id', '=', 'NULL'],
            ['species_id', '=', $catSpeciesId],
        ])->get()->count();

        $this->resetRowNumber();

        return [
            [$this->rowNumber(), 'Собаки', $dogsAmount],
            [$this->rowNumber(), 'Коти', $catsAmount],
            ];

    }


    private static function whereInDateRange($dateFrom, $dateTo)
    {
        return Animal::where([
            ['created_at', '>=', $dateFrom],
            ['created_at', '<=', $dateTo],
        ]);
    }

}