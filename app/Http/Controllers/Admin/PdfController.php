<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Services\Pdf\DataProviders\AnimalPdfDataProvider;
use App\Services\Pdf\PdfGeneratorService;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function animalInfo($id, PdfGeneratorService $generatorService)
    {
        $animal = Animal::findOrFail($id);
        $pdfDataProvider = new AnimalPdfDataProvider($animal);

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'animal.pdf');
    }
}
