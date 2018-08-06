<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{

    public function getBreeds($species)
    {
        $breeds = $species->breeds->pluck('name', 'id')->toArray();

        $breeds = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $breeds, array_keys($breeds));

        return response(json_encode($breeds));
    }

    public function getColors($species)
    {
        $colors = $species->colors->pluck('name', 'id')->toArray();

        $colors = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $colors, array_keys($colors));

        return response(json_encode($colors));
    }

    public function getFurs($species)
    {
        $furs = $species->furs->pluck('name', 'id')->toArray();

        $furs = array_map(function ($value, $key) {
            return [
                'name' => $value,
                'value' => $key
            ];
        }, $furs, array_keys($furs));

        return response(json_encode($furs));
    }

}
