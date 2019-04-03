<?php

namespace App\Http\Controllers\Admin;

use App\Models\Animal;
use App\Services\Printable\DataProviders\AnimalPrintDataProvider;
use App\Services\Printable\PdfPrintService;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function animalInfo($id, PdfPrintService $generatorService)
    {
        $animal = Animal::findOrFail($id);
        $pdfDataProvider = new AnimalPrintDataProvider($animal);

        $generatorService->init($pdfDataProvider, 'print.tables_with_sign_place_pdf', 'animal');

        return $generatorService->download();
    }
}
