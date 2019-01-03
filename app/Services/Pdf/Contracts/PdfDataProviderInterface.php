<?php

namespace App\Services\Pdf\Contracts;


use App\Services\Pdf\DataProviders\Document;

interface PdfDataProviderInterface
{
    public function data(): Document;
}