<?php

namespace App\Services\Pdf;


use App\Services\Pdf\Contracts\PdfDataProviderInterface;
use App\Services\Pdf\Contracts\PdfGeneratorServiceInterface;
use PDF;

class PdfGeneratorService implements PdfGeneratorServiceInterface
{
    public function generateAndDownload(PdfDataProviderInterface $dataProvider, string $view, string $pdfFileName)
    {
        $pdf = PDF::loadView($view, ['document' => $dataProvider->data()]);
        return $pdf->download($pdfFileName);
    }
}