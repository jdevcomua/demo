<?php

namespace App\Helpers;

use Carbon\Carbon;

class Date
{

    /**
     * @param Carbon|null $date
     * @return string
     */
    public static function getlocalizedDate($date)
    {
        if ($date) {
            switch ($date->month) {
                case 1: $m = 'Січня'; break;
                case 2: $m = 'Лютого'; break;
                case 3: $m = 'Березня'; break;
                case 4: $m = 'Квітня'; break;
                case 5: $m = 'Травня'; break;
                case 6: $m = 'Червня'; break;
                case 7: $m = 'Липня'; break;
                case 8: $m = 'Серпня'; break;
                case 9: $m = 'Вересня'; break;
                case 10: $m = 'Жовтня'; break;
                case 11: $m = 'Листопада'; break;
                case 12: $m = 'Грудня'; break;
            }
            return $date->day . ' ' . $m . ' ' . $date->year;
        } else {
            return '-';
        }
    }

    public static function getDiffLocalized(\DateInterval $date)
    {
        $months = [
            'місяць' => [1],
            'місяця' => [2, 3, 4],
        ];

        $years = [
            'рік' => [1],
            'роки' => [2, 3, 4],
        ];

        $yearsPart = '';
        $monthsPart = '';

        if ($date->y) {
            foreach ($years as $k => $v) {
                if (array_search($date->y % 10, $v) !== false) {
                    $yearsPart = $date->y . ' ' . $k;
                }
            }
            if ($yearsPart === '') {
                $yearsPart = $date->y . ' ' . 'років ';
            }
        }

        foreach ($months as $k => $v) {
            if (array_search($date->m % 10, $v) !== false) {
                $monthsPart = $date->m . ' ' . $k;
            }
        }

        if ($monthsPart === '') {
            $monthsPart = $date->m . ' ' . 'місяців';
        }

        $fullDate = $yearsPart . $monthsPart;

        return trim($fullDate);
    }

}
