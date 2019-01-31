<?php

namespace App\Services\Printable\DataProviders;


use App\Services\Printable\Contracts\PrintDataProviderInterface;
use Illuminate\Database\Eloquent\Collection;

class FilteredUsers extends CommonLogicPrintDataProvider implements PrintDataProviderInterface
{
    /**
     * @var Collection
     */
    private $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function data(): Document
    {
        $usersTable = new Table;
        $usersTable
            ->setHeaders([
                '№ з/п',
                'Прізвище',
                'Ім\'я',
                'По батькові',
                'e-mail',
                'Телефон',
                'Дата народження',
                'Паспорт',
                'Адреса',
                'К-сть тварин',
                'Організація',
                'Зареєстровано',
                'Оновлено'
            ])
            ->setColumns($this->users());

        $document = new Document;
        $document
            ->setTitle('Користувачі')
            ->setTables([$usersTable]);

        return $document;
    }

    private function users()
    {
        $usersData = [];

        $this->resetRowNumber();

        foreach ($this->users as $user) {
            $animalsAmount = $user->animals->count();
            $organization = '-';
            if ($user->organization !== null) {
                $organization = $user->organization->name;
            }

            $usersData[] = [
                $this->rowNumber(),
                $user->last_name,
                $user->first_name,
                $user->middle_name,
                $user->emails[0]->email,
                $user->phones[0]->phone,
                $this->localizedDate($user->birthday),
                $user->passport,
                isset($user->addresses[0]) ? $user->addresses[0]->full_address : '-',
                $animalsAmount,
                $organization,
                $this->localizedDate($user->created_at),
                $this->localizedDate($user->updated_at)
            ];
        }

        return $usersData;
    }

}