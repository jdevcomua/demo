<?php

namespace App\Services\Printable\DataProviders;


use App\Models\Animal;
use App\Models\Species;
use App\Services\Printable\Contracts\PrintDataProviderInterface;

class ReportRegisteredAnimalsPrintDataProvider extends CommonLogicPrintDataProvider implements PrintDataProviderInterface
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
        $registeredAnimalsTable = new Table;
        $registeredAnimalsTable
            ->setTitle($this->makeTitle())
            ->setHeaders(['№ з/п', 'Тварини', 'Всього'])
            ->setColumns($this->registeredAnimalsColumns());

        $document = new Document;
        $document
            ->setTitle('Звіт про реєстрацію тварин в системі РДТ за період')
            ->setTables([$registeredAnimalsTable]);

        return $document;
    }

    private function makeTitle()
    {
        return 'Звіт про реєстрацію тварин в системі РДТ за період з '
            . $this->localizedDate($this->dateFrom)
            . 'р. по '
            . $this->localizedDate($this->dateTo)
            . 'р.';
    }

    private function registeredAnimalsColumns()
    {
        $dogSpeciesId = Species::where('name', '=', 'Собака')->first()->id;
        $catSpeciesId = Species::where('name', '=', 'Кiт')->first()->id;

        $dogsAmount = static::whereInDateRange($this->dateFrom, $this->dateTo)->where([
            ['species_id', '=', $dogSpeciesId],
        ])->get()->count();


        $catsAmount = static::whereInDateRange($this->dateFrom, $this->dateTo)->where([
            ['species_id', '=', $catSpeciesId],
        ])->get()->count();

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