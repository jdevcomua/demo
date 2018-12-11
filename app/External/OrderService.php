<?php

namespace App\External;

use Ixudra\Curl\Facades\Curl;

class OrderService
{
    protected $order;
    protected $endpoint;

    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
        $this->endpoint = config('services.kyivID.create_order_endpoint');
    }

    public function sendData()
    {
        if (\Session::exists('kyiv_id_access_token')) {
            Curl::to($this->endpoint)
                ->withHeader('Authorization: ' . 'Bearer ' . \Session::get('kyiv_id_access_token'))
                ->withHeader('Content-Type: ' . 'application/json')
                ->withData($this->order->dataAsArray())->asJson()->post();
        }
    }

}