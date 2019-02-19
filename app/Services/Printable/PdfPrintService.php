<?php

namespace App\Services\Printable;


use App\Services\Printable\Contracts\PrintDataProviderInterface;
use App\Services\Printable\Contracts\PrintServiceInterface;
use PDF;

class PdfPrintService implements PrintServiceInterface
{
    /**
     * @var PDF
     */
    private $pdf;
    private $pdfFileName;
    private $view;

    public function init(PrintDataProviderInterface $dataProvider, string $view, string $pdfFileName)
    {
        $this->pdf = PDF::loadView($view, ['document' => $dataProvider->data()]);
        $this->pdfFileName = $pdfFileName . '.pdf';
        $this->view = $view;
    }


    public function download()
    {
        return $this->pdf->download($this->pdfFileName);
    }
}