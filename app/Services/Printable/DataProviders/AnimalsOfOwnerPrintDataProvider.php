<?php

namespace App\Services\Printable\DataProviders;


use App\Services\Printable\Contracts\PrintDataProviderInterface;
use App\User;

class AnimalsOfOwnerPrintDataProvider extends CommonLogicPrintDataProvider implements PrintDataProviderInterface
{
    private $owner;
    private $animals;

    public function __construct($owner_id)
    {
        $this->owner = User::findOrFail($owner_id);
        $this->animals = $this->owner->animals;
    }

    public function data(): Document
    {
        return $this->animalsTables();
    }

    private function animalsTables()
    {
        $tables = [];

        foreach ($this->animals as $index => $animal) {
            $baseInfoTable = new Table();
            $baseInfoTable
                ->setTitle('Тварина №' . ++$index)
                ->setHeaders(['№ з/п', 'Назва поля', 'Опис'])
                ->setColumns($this->baseInfoColumns($animal));

            $veterinaryMeasuresTable = new Table();
            $veterinaryMeasuresTable
                ->setTitle('Ветеринарні заходи')
                ->setHeaders(['№ з/п', 'Ветеринарний захід', 'Ким проведено', 'Дата'])
                ->setColumns($this->veterinaryMeasuresColumns($animal));

            $offensesTable = new Table();
            $offensesTable
                ->setTitle('Правопорушення')
                ->setHeaders(['№ з/п', 'Правопорушення', 'Опис', 'Зафіксував', 'Наявність укусу'])
                ->setColumns($this->offensesColumns($animal));

            $historyTable = new Table();
            $historyTable
                ->setTitle('Історія')
                ->setHeaders(['№ з/п', 'Дата', 'Опис'])
                ->setColumns($this->historyColumns($animal));
            $tables[] = $baseInfoTable;
            $tables[] = $veterinaryMeasuresTable;
            $tables[] = $offensesTable;
            $tables[] = $historyTable;
        }



        $document = new Document;
        $document
            ->setTitle('Зареєстровані тварини власника: ' . $this->owner->full_name)
            ->enableSignBlock()
            ->setTables($tables);

        return $document;
    }

    private function historyColumns($animal)
    {
        $historyColumns = [];
        foreach($animal->chronicles as $chronicle) {
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

    private function offensesColumns($animal)
    {
        $offensesColumns = [];
        if ($animal->animalOffenses !== null) {
            foreach ($animal->animalOffenses as $animalOffense) {
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

    private function veterinaryMeasuresColumns($animal)
    {
        $veterinaryMeasuresColumns = [];

        $vaccination = $animal->vaccination;
        $sterilization = $animal->sterilization;

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

        if ($animal->animalVeterinaryMeasure !== null) {
            foreach ($animal->animalVeterinaryMeasure as $veterinaryMeasure) {
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

    private function baseInfoColumns($animal): array
    {
        $ownerName = (isset($animal->user) && $animal->user->full_name !== null) ? $animal->user->full_name : '-';
        $badge = $animal->badge ?? '-';
        $clip = $animal->clip ?? '-';
        $chip = $animal->chip ?? '-';
        $gender = $animal->gender ? 'Самка' : 'Самець';
        $verification = $animal->verified ? 'Верифіковано, дата: ' . $this->localizedDate($animal->verification->updated_at) : 'Не верифіковано';

        $baseInfoColumns = [
            [$this->rowNumber(), 'Тварина', $animal->nickname],
            [$this->rowNumber(), 'Тварину загублено', $animal->lost ? 'Так' : 'Ні'],
            $this->archivedAnimalColumn($animal),
            [$this->rowNumber(), 'Дата народження', $this->localizedDate($animal->birthday)],
            [$this->rowNumber(), 'Адреса', $this->address($animal)],
            [$this->rowNumber(), 'Дата реєстрації', $this->localizedDate($animal->created_at)],
            [$this->rowNumber(), 'ПІБ власника', $ownerName],
            [$this->rowNumber(), 'Порода', $animal->breed->name],
            [$this->rowNumber(), 'Жетон', $badge],
            [$this->rowNumber(), 'Кліпса', $clip],
            [$this->rowNumber(), 'Чіп', $chip],
            [$this->rowNumber(), 'Стать', $gender],
            [$this->rowNumber(), 'Шерсть', $animal->fur->name],
            [$this->rowNumber(), 'Окрас', $animal->color->name],
            [$this->rowNumber(), 'Особливі прикмети', $animal->comment ?? '-'],
            [$this->rowNumber(), 'Верифікація', $verification],
        ];
        $this->resetRowNumber();
        return $baseInfoColumns;
    }

    private function archivedAnimalColumn($animal)
    {
        $archivedType = $animal->archived_type;
        $archivedText = 'Ні';

        if ($archivedType) {
            if ($archivedType === 'Смерть') {
                $archivedText = 'Так. Причина архівації: '
                    . $archivedType
                    . '. Причина смерті: '
                    . $animal->archivable->cause_of_death
                    . '. Дата смерті: '
                    . $this->localizedDate($animal->archivable->carbon_died_at);
            } else {
                $archivedText = 'Так. Причина архівації: '
                    . $archivedType
                    . '. Дата виїзду: '
                    . $this->localizedDate($animal->archivable->carbon_moved_at);
            }
        }

        $archivedColumn = [$this->rowNumber(), 'Тварину архівовано', $archivedText];

        return $archivedColumn;
    }

    private function address($animal)
    {
        $address = $this->animal->user->living_address ?? $animal->user->registration_address;
        if ($address === null) {
            return '-';
        }
        return $address->city . ', ' . $address->street . ', будинок ' . $address->building . $address->apartment;
    }

}