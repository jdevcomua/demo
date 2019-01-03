<?php

namespace App\Http\Controllers\Admin;

use App\Services\Pdf\DataProviders\ReportRegisteredAnimalsPdfDataProvider;
use App\Services\Pdf\DataProviders\ReportRegisteredHomelessAnimalsPdfDataProvider;
use App\Services\Pdf\PdfGeneratorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function registeredAnimalsIndex()
    {
        return view('admin.reports.view_report', ['title' => 'Звіт - реєстрація тварин', 'formRoute' => 'admin.reports.registered-animals.generate']);
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
        return view('admin.reports.view_report', ['title' => 'Звіт - реєстрація безпритульних тварин', 'formRoute' => 'admin.reports.registered-animals-homeless.generate',
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

    private function dateConvert($date)
    {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->toDateString();
    }

    private function dateConvertForPicker($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}
