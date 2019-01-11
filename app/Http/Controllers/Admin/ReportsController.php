<?php

namespace App\Http\Controllers\Admin;

use App\Services\Printable\DataProviders\ReportAnimalsByBreedsPrintDataProvider;
use App\Services\Printable\DataProviders\ReportAnimalsBySpeciesPrintDataProvider;
use App\Services\Printable\DataProviders\ReportRegisteredAnimalsOwnersPrintDataProvider;
use App\Services\Printable\DataProviders\ReportRegisteredAnimalsPrintDataProvider;
use App\Services\Printable\DataProviders\ReportRegisteredHomelessAnimalsPrintDataProvider;
use App\Services\Printable\PdfPrintService;
use App\Services\Printable\ReportsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    protected $reportsManager;

    public function __construct(ReportsManager $reportsManager)
    {
        $this->reportsManager = $reportsManager;
    }

    public function registeredAnimalsIndex()
    {
        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація тварин',
            'formRouteDownload' => 'admin.reports.registered-animals.download',
            'formRoute' => 'admin.reports.registered-animals.generate']);
    }

    public function preview(Request $request)
    {
        $this->reportsManager->init($request);
    }

    public function download(Request $request)
    {

    }

    public function registeredAnimalsGenerate(Request $request)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredAnimalsPrintDataProvider($dateFrom, $dateTo);


        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація тварин',
            'formRoute' => 'admin.reports.registered-animals.generate',
            'formRouteDownload' => 'admin.reports.registered-animals.download',
            'viewDocument' => $pdfDataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
            ]);
    }

    public function registeredAnimalsDownload(Request $request, PdfPrintService $generatorService)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));


        $pdfDataProvider = new ReportRegisteredAnimalsPrintDataProvider($dateFrom, $dateTo);

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

        $pdfDataProvider = new ReportRegisteredHomelessAnimalsPrintDataProvider($dateFrom, $dateTo);


        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація безпритульних тварин',
            'formRoute' => 'admin.reports.registered-animals-homeless.generate',
            'formRouteDownload' => 'admin.reports.registered-animals-homeless.download',
            'viewDocument' => $pdfDataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
        ]);
    }

    public function registeredAnimalsHomelessDownload(Request $request, PdfPrintService $generatorService)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredHomelessAnimalsPrintDataProvider($dateFrom, $dateTo);

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'registered_homeless_animals_report.pdf');
    }

    public function animalsAmountBySpecies()
    {
        $pdfDataProvider = new ReportAnimalsBySpeciesPrintDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за видом',
            'form' => 'admin.reports.partials.forms.animals_amount_without_dates',
            'formRouteDownload' => 'admin.reports.animals-amount-species.download',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function animalsAmountBySpeciesDownload(PdfPrintService $generatorService)
    {
        $pdfDataProvider = new ReportAnimalsBySpeciesPrintDataProvider;

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'amount_of_animals_by_species_report.pdf');
    }

    public function animalsAmountByBreeds()
    {
        $pdfDataProvider = new ReportAnimalsByBreedsPrintDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за породою',
            'form' => 'admin.reports.partials.forms.animals_amount_without_dates',
            'formRouteDownload' => 'admin.reports.animals-amount-breeds.download',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function animalsAmountByBreedsDownload(PdfPrintService $generatorService)
    {
        $pdfDataProvider = new ReportAnimalsByBreedsPrintDataProvider;

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'amount_of_animals_by_breeds_report.pdf');
    }

    public function registeredAnimalsOwners()
    {
        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація власників тварин',
            'formRouteDownload' => 'admin.reports.registered-animals-owners.download',
            'formRoute' => 'admin.reports.registered-animals-owners.generate',
        ]);
    }

    public function registeredAnimalsOwnersGenerate(Request $request)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredAnimalsOwnersPrintDataProvider($dateFrom, $dateTo);


        return view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація власників тварин',
            'formRoute' => 'admin.reports.registered-animals-owners.generate',
            'formRouteDownload' => 'admin.reports.registered-animals-owners.download',
            'viewDocument' => $pdfDataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
        ]);
    }

    public function registeredAnimalsOwnersDownload(Request $request, PdfPrintService $generatorService)
    {
        $dateFrom = $this->dateConvert($request->get('dateFrom'));
        $dateTo = $this->dateConvert($request->get('dateTo'));

        $pdfDataProvider = new ReportRegisteredAnimalsOwnersPrintDataProvider($dateFrom, $dateTo);

        return $generatorService->generateAndDownload($pdfDataProvider, 'pdf.tables_with_sign_place_pdf', 'registered_animals_owners_report.pdf');
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
