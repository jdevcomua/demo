<?php

namespace App\Services\Printable\DataProviders;


class Document
{
    private $title;
    private $tables;
    private $signBlock = false;
    private $signText = "Начальник служби обліку<br> та реєстрації тварин";
    public  $print;

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

    public function signBlock()
    {
        return $this->signBlock;
    }

    public function enableSignBlock()
    {
        $this->signBlock = true;
        return $this;
    }

    public function disableSignBlock()
    {
        $this->signBlock = false;
        return $this;
    }

    public function signText()
    {
        return $this->signText;
    }

    public function setSignText($text)
    {
        $this->signText = $text;
        return $this;
    }

    public function titleNoHtml()
    {
        if ($this->title === null) return null;
        return preg_replace('/<[^>]*>/', '', $this->title);
    }
}