<?php

namespace App\External;

class OrderService
{
    protected $endpoint;

    public function __construct()
    {
        $this->endpoint = config('services.kyivID.create_order_endpoint');
    }

    public function sendData(OrderInterface $order)
    {
        if (\Session::exists('kyiv_id_access_token')) {
            (new \GuzzleHttp\Client())->post($this->endpoint, [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . \Session::get('kyiv_id_access_token'),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($order->dataAsArray())
            ]);
        }
    }
}
