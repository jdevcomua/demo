<?php

namespace App\Services\Pdf\DataProviders;


class Table
{
    public $headers;
    public $columns;
    public $title;

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
}