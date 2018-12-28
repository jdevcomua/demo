<?php

namespace App\External;


interface OrderInterface
{
    /**
     * @return array
     * Returns data for sending to the endpoint
     */
    public function dataAsArray();

    /**
     * @return string
     * Returns data for sending to the endpoint
     */
    public function dataAsJson();
}