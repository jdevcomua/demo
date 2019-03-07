<?php

namespace App\Listeners;

use App\Events\AnimalAdded;
use App\External\AnimalAddedOrder;
use App\External\OrderService;
use App\Mail\CustomMail;
use Carbon\Carbon;

class AnimalAddedEventListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AnimalAdded $event
     * @return void
     */
    public function handle($event)
    {
        $html = "
<h4>Вітаємо!</h4>
<p>Ви додали вашого улюбленця на сайті \"Реєстр домашніх тварин\".</p>
<p>Для закінчення реєстрації тварини Вам необхідно звернутися до Київської міської лікарні ветеринарної медицини, за адресою:</p>
<b>місто Київ</b><br><b>Електротехнічна, 5А</b><br><b>тел. +38 (044) 366 69 99</b><p></p>
<p><br></p>
<p>Час роботи:</p>
<p>Пн-Чт 9:00 - 18:00 год</p>
<p>Пт 9:00 - 16:45 год</p>
<p>Обід 13:00 -14:00 год</p>
<br><p><b>При собі мати:</b></p>
- документ, що посвідчує особу <br>- ветеринарний паспорт <br>- тварину.&nbsp;<p></p>
<p><br></p>
<p>Більш детальну інформацію про реєстрацію тварин можна переглянути на сайті КП \"КМЛВМ\" https://bit.ly/2ISR5jH. </p>
<br><p>З повагою, команда Kyiv Smart City</p>
";
        $message = (new CustomMail('Вітаємо!', $html))
            ->onQueue('default')
            ->delay(Carbon::now()->addSecond());

        \Mail::to(\Auth::user()->primary_email)
            ->queue($message);
        $order = new AnimalAddedOrder($event->user, $event->animal);
        $orderService = new OrderService($order);
        $orderService->sendData();
    }


}
