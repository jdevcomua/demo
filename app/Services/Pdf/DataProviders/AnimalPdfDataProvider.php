<?php

namespace App\Services\Pdf\DataProviders;


use App\Models\Animal;
use App\Services\Pdf\Contracts\PdfDataProviderInterface;

class AnimalPdfDataProvider extends CommonLogicPdfDataProvider implements PdfDataProviderInterface
{
    private $animal;

    public function __construct(Animal $animal)
    {
        $this->animal = $animal;
    }

    public function data(): Document
    {
        $baseInfoTable = new Table();
        $baseInfoTable
            ->setHeaders(['№ з/п', 'Назва поля', 'Опис'])
            ->setColumns($this->baseInfoColumns());

        $veterinaryMeasuresTable = new Table();
        $veterinaryMeasuresTable
            ->setTitle('Ветеринарні заходи')
            ->setHeaders(['№ з/п', 'Ветеринарний захід', 'Ким проведено', 'Дата'])
            ->setColumns($this->veterinaryMeasuresColumns());

        $offensesTable = new Table();
        $offensesTable
            ->setTitle('Правопорушення')
            ->setHeaders(['№ з/п', 'Правопорушення', 'Опис', 'Зафіксував', 'Наявність укусу'])
            ->setColumns($this->offensesColumns());

        $historyTable = new Table();
        $historyTable
            ->setTitle('Історія')
            ->setHeaders(['№ з/п', 'Дата', 'Опис'])
            ->setColumns($this->historyColumns());


        $document = new Document;
        $document
            ->setTitle('Реєстраційна картка тварини')
            ->setTables([$baseInfoTable, $veterinaryMeasuresTable, $offensesTable, $historyTable]);

        return $document;
    }

    private function historyColumns()
    {
        $historyColumns = [];
        foreach($this->animal->chronicles as $chronicle) {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $chronicle->created_at);
            $historyColumns[] = [
                $this->rowNumber(),
                $this->localizedDate($date),
                $chronicle->text
            ];
        }
        $this->resetRowNumber();
        return $historyColumns;
    }

    private function offensesColumns()
    {
        $offensesColumns = [];
        if ($this->animal->animalOffenses !== null) {
            foreach ($this->animal->animalOffenses as $animalOffense) {
                $offensesColumns[] = [
                    $this->rowNumber(),
                    $animalOffense->offense->name,
                    $animalOffense->offenseAffiliation->name
                    . '. Дата правопорушення: '
                    . $this->localizedDate($animalOffense->date)
                    . '. Номер протоколу: '
                    . $animalOffense->protocol_number
                    . '. Дата протоколу: '
                    . $this->localizedDate($animalOffense->protocol_date),
                    $animalOffense->made_by,
                    $animalOffense->bite ? 'Так' : 'Ні',
                ];
            }
        }
        $this->resetRowNumber();
        return $offensesColumns;
    }

    private function veterinaryMeasuresColumns()
    {
        $veterinaryMeasuresColumns = [];

        $vaccination = $this->animal->vaccination;
        $sterilization = $this->animal->sterilization;

        if ($vaccination !== null) {
            $veterinaryMeasuresColumns[] = [
                $this->rowNumber(),
                'Щеплення проти сказу',
                $vaccination->made_by,
                $this->localizedDate($vaccination->date)
            ];
        }

        if ($sterilization !== null) {
            $veterinaryMeasuresColumns[] = [
                $this->rowNumber(),
                'Стерилізація',
                $sterilization->made_by,
                $this->localizedDate($sterilization->date)
            ];
        }

        if ($this->animal->animalVeterinaryMeasure !== null) {
            foreach ($this->animal->animalVeterinaryMeasure as $veterinaryMeasure) {
                $veterinaryMeasuresColumns[] = [
                    $this->rowNumber(),
                    $veterinaryMeasure->veterinaryMeasure->name,
                    $veterinaryMeasure->made_by,
                    $this->localizedDate($veterinaryMeasure->date)
                ];
            }
        }

        $this->resetRowNumber();
        return $veterinaryMeasuresColumns;
    }

    private function baseInfoColumns(): array
    {
        $ownerName = (isset($this->animal->user) && $this->animal->user->full_name !== null) ? $this->animal->user->full_name : '-';
        $badge = $this->animal->badge ?? '-';
        $clip = $this->animal->clip ?? '-';
        $chip = $this->animal->chip ?? '-';
        $gender = $this->animal->gender ? 'Самка' : 'Самець';
        $verification = $this->animal->verified ? 'Верифіковано, дата: ' . $this->localizedDate($this->animal->verification->updated_at) : 'Не верифіковано';

        $baseInfoColumns = [
            [$this->rowNumber(), 'Тварина', $this->animal->nickname],
            [$this->rowNumber(), 'Тварину загублено', $this->animal->lost ? 'Так' : 'Ні'],
            $this->archivedAnimalColumn(),
            [$this->rowNumber(), 'Дата народження', $this->localizedDate($this->animal->birthday)],
            [$this->rowNumber(), 'Адреса', $this->address()],
            [$this->rowNumber(), 'Дата реєстрації', $this->localizedDate($this->animal->created_at)],
            [$this->rowNumber(), 'ПІБ власника', $ownerName],
            [$this->rowNumber(), 'Порода', $this->animal->breed->name],
            [$this->rowNumber(), 'Жетон', $badge],
            [$this->rowNumber(), 'Кліпса', $clip],
            [$this->rowNumber(), 'Чіп', $chip],
            [$this->rowNumber(), 'Стать', $gender],
            [$this->rowNumber(), 'Шерсть', $this->animal->fur->name],
            [$this->rowNumber(), 'Окрас', $this->animal->color->name],
            [$this->rowNumber(), 'Особливі прикмети', $this->animal->comment ?? '-'],
            [$this->rowNumber(), 'Верифікація', $verification],
        ];
        $this->resetRowNumber();
        return $baseInfoColumns;
    }

    private function archivedAnimalColumn()
    {
        $archivedType = $this->animal->archived_type;
        $archivedText = 'Ні';

        if ($archivedType) {
            if ($archivedType === 'Смерть') {
                $archivedText = 'Так. Причина архівації: '
                    . $archivedType
                    . '. Причина смерті: '
                    . $this->animal->archivable->cause_of_death
                    . '. Дата смерті: '
                    . $this->localizedDate($this->animal->archivable->carbon_died_at);
            } else {
                $archivedText = 'Так. Причина архівації: '
                    . $archivedType
                    . '. Дата виїзду: '
                    . $this->localizedDate($this->animal->archivable->carbon_moved_at);
            }
        }

        $archivedColumn = [$this->rowNumber(), 'Тварину архівовано', $archivedText];

        return $archivedColumn;
    }

    private function address()
    {
        $address = $this->animal->user->living_address ?? $this->animal->user->registration_address;
        if ($address === null) {
            return '-';
        }
        return $address->city . ', ' . $address->street . ', будинок ' . $address->building . $address->apartment;
    }

}