<?php

namespace App\Filters;


use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UsersFilter
{
    private $requestData;

    /**
     * @param Request $request
     * @param User $user
     * @return Collection
     */
    public function process(Request $request): Builder
    {
        $user = new User;
        $this->requestData = $request->all();

        $query = $user->newQuery();
        $query = $this->processOrganization($query);
        $query = $this->processAnimalsAmount($query);
        $query = $this->processBirthday($query);
        $query = $this->processCreateDate($query);
        $query = $this->processUpdateDate($query);

        return $query;
    }



    private function processOrganization(Builder $query): Builder
    {
        return !$this->skip('organization') ? $query->where('organization_id', '=', $this->requestData['organization']) : $query;
    }

    private function processAnimalsAmount(Builder $query): Builder
    {
        $hasMin = !$this->skip('animalsAmountMin');
        $hasMax = !$this->skip('animalsAmountMax');

        if ($hasMin || $hasMax) {
            $query = $query->withCount('animals');
            if ($hasMin && $hasMax) {
                return $query->whereHas('animals', function($query) {
                    $query->groupBy('user_id')->havingRaw('COUNT(*) >= ' . $this->requestData['animalsAmountMin']);
                }, '<=', $this->requestData['animalsAmountMax']);

            }

            elseif ($hasMax) {
                return $query->has('animals', '<=', $this->requestData['animalsAmountMax']);
            }

            elseif ($hasMin) {
                return $query->has('animals', '>=', $this->requestData['animalsAmountMin']);
            }
        }
        return $query;
    }

    private function processBirthday(Builder $query): Builder
    {
        return $this->commonProcessDate(
            $query,
            'birthday',
            'birthdayMin',
            'birthdayMax'
        );
    }

    private function processCreateDate(Builder $query): Builder
    {
        return $this->commonProcessDate(
            $query,
            'created_at',
            'createdAtMin',
            'createdAtMax'
        );

    }

    private function processUpdateDate(Builder $query): Builder
    {
        return $this->commonProcessDate(
            $query,
            'updated_at',
            'updatedAtMin',
            'updatedAtMax'
        );
    }

    private function processSearch()
    {
        //
    }

    private function skip(string $requestProperty): bool
    {
        if ($this->requestData[$requestProperty] === '-' || $this->requestData[$requestProperty] === null) {
            return true;
        }
        return false;
    }

    private function convertToDateTime(string $dateString): ?string
    {
        if ($dateString === null || $dateString === '-') return null;

        $dateArray = explode('.', $dateString);
        $day = $dateArray[0];
        $month = $dateArray[1];
        $year = $dateArray[2];

        return $year . '-' . $month . '-' . $day;
    }

    private function commonProcessDate(Builder $query, string $dateColumnName, string $dateMinName, string $dateMaxName): Builder
    {
        $hasMin = !$this->skip($dateMinName);
        $hasMax = !$this->skip($dateMaxName);

        if ($hasMin || $hasMax) {
            $dateMin  = $this->convertToDateTime($this->requestData[$dateMinName]);
            $dateMax  = $this->convertToDateTime($this->requestData[$dateMaxName]);

            if ($hasMin && $hasMax) {
                $query = $query->whereBetween($dateColumnName, [$dateMin, $dateMax]);
            }
            elseif ($hasMin) {
                $query = $query->where($dateColumnName, '>=', $dateMin);
            } else {
                $query = $query->where($dateColumnName, '<=', $dateMax);
            }
        }

        return $query;
    }
}