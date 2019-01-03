<?php

namespace App\Http\Controllers\Admin;

use App\Services\Pdf\DataProviders\ReportAnimalsByBreedsPdfDataProvider;
use App\Services\Pdf\DataProviders\ReportAnimalsBySpeciesPdfDataProvider;
use App\Services\Pdf\DataProviders\ReportRegisteredAnimalsPdfDataProvider;
use App\Services\Pdf\DataProviders\ReportRegisteredHomelessAnimalsPdfDataProvider;
use App\Services\Pdf\PdfGeneratorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function registeredAnimalsIndex()
    {
        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація тварин',
            'formRouteDownload' => 'admin.reports.registered-animals.download',
            'formRoute' => 'admin.reports.registered-animals.generate']);
    }

    public function registeredAnimalsGenerate(Request $request)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredAnimalsPdfDataProvider($dateFrom, $dateTo);


        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація тварин',
            'formRoute' => 'admin.reports.registered-animals.generate',
            'formRouteDownload' => 'admin.reports.registered-animals.download',
            'viewDocument' => $pdfDataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
            ]);
    }

    public function registeredAnimalsDownload(Request $request, PdfGeneratorService $generatorService)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));


        $pdfDataProvider = new ReportRegisteredAnimalsPdfDataProvider($dateFrom, $dateTo);

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'registered_animals_report.pdf');
    }

    public function registeredAnimalsHomelessIndex()
    {
        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація безпритульних тварин',
            'formRouteDownload' => 'admin.reports.registered-animals-homeless.download',
            'formRoute' => 'admin.reports.registered-animals-homeless.generate',
        ]);
    }

    public function registeredAnimalsHomelessGenerate(Request $request)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredHomelessAnimalsPdfDataProvider($dateFrom, $dateTo);


        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація безпритульних тварин',
            'formRoute' => 'admin.reports.registered-animals-homeless.generate',
            'formRouteDownload' => 'admin.reports.registered-animals-homeless.download',
            'viewDocument' => $pdfDataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
        ]);
    }

    public function registeredAnimalsHomelessDownload(Request $request, PdfGeneratorService $generatorService)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredHomelessAnimalsPdfDataProvider($dateFrom, $dateTo);

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'registered_homeless_animals_report.pdf');
    }

    public function animalsAmountBySpecies()
    {
        $pdfDataProvider = new ReportAnimalsBySpeciesPdfDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за видом',
            'form' => 'admin.reports.partials.forms.animals_amount_without_dates',
            'formRouteDownload' => 'admin.reports.animals-amount-species.download',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function animalsAmountBySpeciesDownload(PdfGeneratorService $generatorService)
    {
        $pdfDataProvider = new ReportAnimalsBySpeciesPdfDataProvider;

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'amount_of_animals_by_species_report.pdf');
    }

    public function animalsAmountByBreeds()
    {
        $pdfDataProvider = new ReportAnimalsByBreedsPdfDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за породою',
            'form' => 'admin.reports.partials.forms.animals_amount_without_dates',
            'formRouteDownload' => 'admin.reports.animals-amount-breeds.download',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function animalsAmountByBreedsDownload(PdfGeneratorService $generatorService)
    {
        $pdfDataProvider = new ReportAnimalsByBreedsPdfDataProvider;

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'amount_of_animals_by_breeds_report.pdf');
    }

    private function dateConvert($date)
    {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->toDateString();
    }

    private function dateConvertForPicker($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}
