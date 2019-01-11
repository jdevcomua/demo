<?php

namespace App\Services\Printable\DataProviders;


use App\Services\Printable\Contracts\PrintDataProviderInterface;
use App\User;

class ReportRegisteredAnimalsOwnersPrintDataProvider extends CommonLogicPrintDataProvider implements PrintDataProviderInterface
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
        $registeredAnimalsOwnersTable = new Table;
        $registeredAnimalsOwnersTable
            ->setTitle($this->makeTitle())
            ->setHeaders(['№ з/п', 'Вид особи', 'Всього'])
            ->setColumns($this->registeredAnimalsOwnersColumns());

        $document = new Document;
        $document
            ->setTitle('Звіт про реєстрацію власників тварин в системі РДТ за період')
            ->setTables([$registeredAnimalsOwnersTable]);

        return $document;
    }

    private function makeTitle()
    {
        return 'Звіт про реєстрацію власників тварин в системі РДТ за період з '
            . $this->localizedDate($this->dateFrom)
            . 'р. по '
            . $this->localizedDate($this->dateTo)
            . 'р.';
    }

    private function registeredAnimalsOwnersColumns()
    {
        $usersAmount = 0;
        $users = static::whereInDateRange($this->dateFrom, $this->dateTo)->get();

        foreach ($users as $user) {
            if ($user->animals->count() > 0) {
                $usersAmount++;
            }
        }

        $this->resetRowNumber();

        //0 is just a placeholder
        return [
            [$this->rowNumber(), 'Фізичні особи', $usersAmount],
            [$this->rowNumber(), 'Юридичні особи', 0],
            ];

    }


    private static function whereInDateRange($dateFrom, $dateTo)
    {
        return User::where([
            ['created_at', '>=', $dateFrom],
            ['created_at', '<=', $dateTo],
        ]);
    }

}