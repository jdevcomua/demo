<?php

namespace App\Services\Pdf\DataProviders;


abstract class CommonLogicPdfDataProvider
{
    protected $rowNumber;

    protected function rowNumber()
    {
        return ++$this->rowNumber;
    }

    protected function resetRowNumber()
    {
        $this->rowNumber = 0;
    }

    protected function localizedDate($date)
    {
        if (!$date instanceof \Carbon\Carbon) {
            $date = $this->convertToCarbon($date);
        }
        return \App\Helpers\Date::getlocalizedDate($date);
    }

    protected function convertToCarbon($date)
    {
        return \Carbon\Carbon::parse($date);
    }
}