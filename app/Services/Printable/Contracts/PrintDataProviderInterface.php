<?php

namespace App\Services\Printable\Contracts;


use App\Services\Printable\DataProviders\Document;

interface PrintDataProviderInterface
{
    public function data(): Document;
}