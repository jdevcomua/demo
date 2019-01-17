<?php

namespace App\Services\Printable;


use App\Services\Printable\Exceptions\NoReportMethodFoundException;
use Illuminate\Http\Request;

class ReportsManager
{
    protected $reportName;
    protected $request;
    protected $dataProvider;

    protected $dataProvidersMapping = [
        'registeredAnimals' => 'App\Services\Printable\DataProviders\ReportRegisteredAnimalsPrintDataProvider',
        'registeredAnimalsHomeless' => 'App\Services\Printable\DataProviders\ReportRegisteredHomelessAnimalsPrintDataProvider',
        'registeredAnimalsOwners' => 'App\Services\Printable\DataProviders\ReportRegisteredAnimalsOwnersPrintDataProvider',
        'animalsAmountBySpecies' => 'App\Services\Printable\DataProviders\ReportAnimalsBySpeciesPrintDataProvider',
        'animalsAmountByBreeds' => 'App\Services\Printable\DataProviders\ReportAnimalsByBreedsPrintDataProvider',
        'registeredAnimalsOfOwner' => 'App\Services\Printable\DataProviders\AnimalsOfOwnerPrintDataProvider',
    ];

    protected $reportNamesTypesMapping = [
        'reportWithDates' => [
            'registeredAnimals',
            'registeredAnimalsHomeless',
            'registeredAnimalsOwners'
        ],
        'reportNoForm' => [
            'animalsAmountBySpecies',
            'animalsAmountByBreeds'
        ],
        'reportOwner' => [
            'registeredAnimalsOfOwner'
        ]
    ];

    protected $pageTitles = [
        'registeredAnimals' => 'Звіт - реєстрація тварин',
        'registeredAnimalsHomeless' => 'Звіт - реєстрація безпритульних тварин',
        'registeredAnimalsOwners' => 'Звіт - реєстрація власників тварин',
        'animalsAmountBySpecies' => 'Звіт - кількість тварин за видом',
        'animalsAmountByBreeds' => 'Звіт - кількість тварин за породою',
        'registeredAnimalsOfOwner' => 'Довідка за тваринами, що зареєстровані на певного власника'
    ];

    public function init(Request $request)
    {
        $this->reportName = $request->get('reportName');
        $this->request = $request;
    }


    public function preview()
    {
        $viewMethod = $this->getReportMethodName() . 'Preview';
        return $this->$viewMethod();
    }

    public function download()
    {
        $downloadMethod = $this->getReportMethodName() . 'Download';
        return $this->$downloadMethod();
    }

    protected function reportWithDatesPreview()
    {
        $dateFrom = $this->dateConvert($this->request->get('dateFrom'));
        $dateTo = $this->dateConvert($this->request->get('dateTo'));

        $dataProviderClass = $this->getDataProviderClass();
        $this->dataProvider = new $dataProviderClass($dateFrom, $dateTo);

        return view('admin.reports.view_report', [
            'title' => $this->getPageTitle(),
            'form' => 'admin.reports.partials.forms.report_with_dates_form',
            'reportName' => $this->reportName,
            'viewDocument' => $this->dataProvider->data(),
            'dateFrom' => $this->dateConvertForPicker($dateFrom),
            'dateTo' => $this->dateConvertForPicker($dateTo),
        ]);
    }

    protected function reportWithDatesDownload()
    {
        $dateFrom = $this->dateConvert($this->request->get('dateFrom'));
        $dateTo = $this->dateConvert($this->request->get('dateTo'));


        $dataProviderClass = $this->getDataProviderClass();
        $this->dataProvider = new $dataProviderClass($dateFrom, $dateTo);

        $printService = new PdfPrintService();
        $printService->init($this->dataProvider, 'print.tables_with_sign_place_pdf', $this->reportName . '.pdf');
        return $printService->download();
    }

    protected function reportNoFormDownload()
    {
        $dataProviderClass = $this->getDataProviderClass();
        $this->dataProvider = new $dataProviderClass();

        $printService = new PdfPrintService();
        $printService->init($this->dataProvider, 'print.tables_with_sign_place_pdf', $this->reportName . '.pdf');

        return $printService->download();
    }

    protected function reportOwnerDownload()
    {
        $owner_id = $this->request->get('owner_id');
        $dataProviderClass = $this->getDataProviderClass();
        $this->dataProvider = new $dataProviderClass($owner_id);

        $printService = new PdfPrintService();
        $printService->init($this->dataProvider, 'print.tables_with_sign_place_pdf', $this->reportName . '.pdf');

        return $printService->download();
    }

    protected function reportOwnerPreview()
    {
        $owner_id = $this->request->get('owner_id');
        $dataProviderClass = $this->getDataProviderClass();
        $this->dataProvider = new $dataProviderClass($owner_id);

        return view('admin.reports.view_report', [
            'title' => $this->getPageTitle(),
            'form' => 'admin.reports.partials.forms.report_select_owner_form',
            'reportName' => $this->reportName,
            'viewDocument' => $this->dataProvider->data(),
            'ownerId' => $owner_id,
        ]);
    }

    protected function dateConvert($date)
    {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->toDateString();
    }

    protected function dateConvertForPicker($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    protected function getReportMethodName(): string
    {
        foreach ($this->reportNamesTypesMapping as $index => $value) {
            if (array_search($this->reportName, $value) !== false) {
                return $index;
            }
        }
        throw new NoReportMethodFoundException();
    }

    protected function getPageTitle(): string
    {
        return $this->pageTitles[$this->reportName];
    }

    protected function getDataProviderClass(): string
    {
        return $this->dataProvidersMapping[$this->reportName];
    }
}