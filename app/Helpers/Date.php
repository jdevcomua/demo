<?php

namespace App\Helpers;

use Carbon\Carbon;

class Date
{

    public static function getlocalizedDate(Carbon $date)
    {
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
    }

}