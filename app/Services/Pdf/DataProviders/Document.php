<?php

namespace App\Services\Pdf\DataProviders;


class Document
{
    private $title;
    private $tables;

    public function setTitle(string $title): Document
    {
        $this->title = $title;
        return $this;
    }

    public function setTables(array $tables): Document
    {
        $this->tables = $tables;

        foreach ($this->tables as $index => $table) {
            if (!count($table->columns)) {
                unset($this->tables[$index]);
            }
        }
        return $this;
    }

    public function tables(): array
    {
        return $this->tables;
    }

    public function title(): string
    {
        return $this->title;
    }
}