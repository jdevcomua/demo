<?php

namespace App\Services\Printable\Contracts;


interface PrintServiceInterface
{
    public function init(PrintDataProviderInterface $dataProvider, string $view, string $pdfFileName);
    public function preview();
    public function download();
}