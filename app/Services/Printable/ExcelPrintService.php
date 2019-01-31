<?php

namespace App\Services\Printable;


use App\Services\Printable\Contracts\PrintDataProviderInterface;
use App\Services\Printable\Contracts\PrintServiceInterface;
use App\Services\Printable\DataProviders\Document;
use App\Services\Printable\DataProviders\Table;
use Maatwebsite\Excel\Exceptions\LaravelExcelException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class ExcelPrintService implements PrintServiceInterface
{
    private $cellsMapping = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];
    /**
     * @var $excelFile LaravelExcelWriter
     */
    private $excelFile;
    /**
     * @var $dataProvider PrintDataProviderInterface
     */
    private $dataProvider;
    /**
     * @var $document Document
     */
    private $document;
    /**
     * @var Table[]
     */
    private $tables;
    private $mergeCells;
    private $titleRowsIndexes;

    public function init(PrintDataProviderInterface $dataProvider, string $view, string $filename): void
    {
        $this->dataProvider = $dataProvider;
        $this->document = $dataProvider->data();
        $this->tables = $this->document->tables();

        $this->excelFile = Excel::create($filename, function ($excel) {
            $excel->sheet('Sheet', function ($sheet) {
                $rows = $this->prepareData();
                foreach ($rows as $i => $row) {
                    $sheet->row(++$i, $row);
                }
                $this->makeCellsMerge($sheet);
                $this->formatCells($sheet);
                $this->makeHeadersBold($sheet);
            });
        });
    }

    public function download(): void
    {
        try {
            $this->excelFile->export('xlsx');
        } catch (LaravelExcelException $e) {
        }
    }


    private function formatCells(&$sheet)
    {
        if ($this->document->titleNoHtml() !== null) {
            $sheet->getStyle('A1')->getFont()->setBold( true );
        }
        foreach ($this->titleRowsIndexes as $titleRowsIndex) {
            $sheet->getStyle('A' . $titleRowsIndex)
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        foreach ($this->tables as $table) {
            $tableRowStart = $table->getExcelStartRange();
            $tableRowEnd = $table->getExcelEndRange();

            for($i = $tableRowStart['index']; $i <= $tableRowEnd['index']; $i++) {
                $rangeString = $tableRowStart['letterFrom'] . $i . ':' . $tableRowStart['letterTo'] . $i;
                $this->alignLeft($sheet, $rangeString);
                $this->setBorders($sheet, $rangeString);
            }
        }
    }

    private function makeHeadersBold(&$sheet)
    {
        foreach ($this->tables as $table) {
            $tableStartRange = $table->getExcelStartRange();
            $startCell = $tableStartRange['letterFrom'];
            $endCell = $tableStartRange['letterTo'];
            $shiftFromTop = $tableStartRange['index'];

            $cellsRange = $startCell . $shiftFromTop . ':' . $endCell . $shiftFromTop;

            $sheet->getStyle($cellsRange)->getFont()->setBold( true );
        }
    }


    private function alignLeft(&$sheet, $rangeString)
    {
        $sheet->getStyle($rangeString)
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    }

    //TODO: make borders work
    private function setBorders(&$sheet, $rangeString)
    {
        $sheet->getStyle($rangeString)
            ->getBorders()
            ->getTop()
            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $sheet->getStyle($rangeString)
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $sheet->getStyle($rangeString)
            ->getBorders()
            ->getLeft()
            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $sheet->getStyle($rangeString)
            ->getBorders()
            ->getRight()
            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
    }

    private function prepareData(): array
    {
        $preparedData = [];
        $tables = $this->document->tables();

        if ($this->document->titleNoHtml() !== null) {
            $this->addTitleRow($preparedData, $this->document->titleNoHtml(), $tables[0]);
        }
        /**
         * @var $table Table
         */
        foreach ($tables as $table) {
            $headers = $table->headers;
            $rows = $table->columns;
            if ($table->title !== null) {
                $this->addTitleRow($preparedData, $table->title, $table);
            }
            $preparedData[] = $headers;
            $this->setStartRange($table, $preparedData);
            foreach ($rows as $row) {
                $preparedData[] = $row;
            }
            $this->setEndRange($table, $preparedData);
        }
        return $preparedData;
    }

    /**
     * @param $table Table
     */
    private function setStartRange(&$table, $rows)
    {
        $colStart = 'A';
        $rangeIndex = $table->getAmountOfCols() - 1;
        $colEnd = $this->cellsMapping[$rangeIndex];
        $rowStart = count($rows);

        $startRange = [
            'index' => $rowStart,
            'letterFrom' => $colStart,
            'letterTo' => $colEnd
        ];
        $table->setExcelStartRange($startRange);
    }

    /**
     * @param $table Table
     */
    private function setEndRange(&$table, $rows)
    {
        $colStart = 'A';
        $rangeIndex = $table->getAmountOfCols() - 1;
        $colEnd = $this->cellsMapping[$rangeIndex];
        $rowEnd = count($rows);

        $endRange = [
            'index' => $rowEnd,
            'letterFrom' => $colStart,
            'letterTo' => $colEnd
        ];
        $table->setExcelEndRange($endRange);
    }

    private function makeCellsMerge(&$sheet)
    {
        foreach ($this->mergeCells as $mergeCell) {
            $sheet->mergeCells($mergeCell);
        }
    }

    private function addTitleRow(&$rows, $title, $table): void
    {
        $titleRow = $this->getTitleRow($title, $table);
        $titleRowPositionIndex = $this->insertTitleRow($rows, $titleRow);
        $this->addMergeCells($titleRowPositionIndex, $table);
    }


    //Note: Merge is in use only for titles right now
    private function addMergeCells($mergeIndex, $table): void
    {
        $colsAmount = $table->getAmountOfCols();
        $this->mergeCells[] = $this->cellsMapping[0] . $mergeIndex . ':' . $this->cellsMapping[$colsAmount-1] . $mergeIndex;
        $this->titleRowsIndexes[] = $mergeIndex;
    }


    private function getTitleRow($title, $table): array
    {
        $titleRow = [];
        $titleRow[] = $title;

        for ($i = 1; $i < $table->getAmountOfCols(); $i++) {
            $titleRow[] = '';
        }
        return $titleRow;
    }

    private function insertTitleRow(&$rows, $titleRow): int
    {
        $rows[] = $titleRow;
        return count($rows);
    }
}