<?php

namespace App\Services\Pdf\Contracts;
use PDF;


interface PdfGeneratorServiceInterface
{
    public function generateAndDownload(PdfDataProviderInterface $dataProvider, string $view, string $pdfFileName);
}