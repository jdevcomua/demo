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

    public function registeredAnimalsIndex(Request $request)
    {
        return $this->preview($request, view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація тварин',
            'form' => 'admin.reports.partials.forms.report_with_dates_form',
            'reportName' => 'registeredAnimals'
        ]));
    }

    public function registeredAnimalsHomelessIndex(Request $request)
    {
        return $this->preview($request, view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація безпритульних тварин',
            'form' => 'admin.reports.partials.forms.report_with_dates_form',
            'reportName' => 'registeredAnimalsHomeless'
        ]));
    }

    public function animalsAmountBySpeciesIndex()
    {
        $pdfDataProvider = new ReportAnimalsBySpeciesPrintDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за видом',
            'form' => 'admin.reports.partials.forms.no_form',
            'reportName' => 'animalsAmountBySpecies',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function registeredAnimalsOwners(Request $request)
    {
        return $this->preview($request, view('admin.reports.view_report', [
            'title' => 'Звіт - реєстрація власників тварин',
            'form' => 'admin.reports.partials.forms.report_with_dates_form',
            'reportName' => 'registeredAnimalsOwners'
        ]));
    }

    public function animalsAmountByBreeds()
    {
        $pdfDataProvider = new ReportAnimalsByBreedsPrintDataProvider;

        return view('admin.reports.view_report', [
            'title' => 'Звіт - кількість тварин за породою',
            'form' => 'admin.reports.partials.forms.no_form',
            'reportName' => 'animalsAmountBySpecies',
            'viewDocument' => $pdfDataProvider->data(),
        ]);
    }

    public function registeredAnimalsOfOwner(Request $request)
    {
        return $this->preview($request, view('admin.reports.view_report', [
            'title' => 'Довідка за тваринами, що зареєстровані на певного власника',
            'form' => 'admin.reports.partials.forms.report_select_owner_form',
            'reportName' => 'registeredAnimalsOfOwner',
//            'viewDocument' => $pdfDataProvider->data(),
        ]));
    }

    protected function preview(Request $request, $view)
    {
        if ($request->isMethod('post')) {
            $this->reportsManager->init($request);
            return $this->reportsManager->preview();
        }
        return $view;
    }

    public function download(Request $request)
    {
        $this->reportsManager->init($request);
        return $this->reportsManager->download();
    }
}
