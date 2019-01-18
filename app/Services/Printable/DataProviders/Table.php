<?php

namespace App\Services\Printable\DataProviders;


class Table
{
    public $headers;
    public $columns;
    public $title;
    private $excelStartRange;
    private $excelEndRange;

    public function setTitle($title): Table
    {
        $this->title = $title;
        return $this;
    }

    public function setHeaders(array $headers): Table
    {
        $this->headers = $headers;
        return $this;
    }

    public function setColumns(array $columns): Table
    {
        $this->columns = $columns;
        return $this;
    }

    public function getAmountOfCols()
    {
        return count($this->headers);
    }

    public function setExcelStartRange(array $startRange)
    {
        $this->excelStartRange = $startRange;
    }

    public function setExcelEndRange(array $endRange)
    {
        $this->excelEndRange = $endRange;
    }

    public function getExcelStartRange()
    {
        return $this->excelStartRange;
    }

    public function getExcelEndRange()
    {
        return $this->excelEndRange;
    }
}