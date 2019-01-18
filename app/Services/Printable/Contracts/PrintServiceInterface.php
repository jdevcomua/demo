<?php

namespace App\Services\Printable\Contracts;


interface PrintServiceInterface
{
    public function init(PrintDataProviderInterface $dataProvider, string $view, string $filename);
    public function download();
}