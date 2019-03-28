<?php

namespace App\Events;


class BadgeScanned extends SendEmailEvent
{
    public static $display_name = 'Відбулося сканування QR-коду жетону';

    public function __construct($email)
    {
        parent::__construct($email);

        $this->setSubject('Відбулося сканування QR-коду жетону');
        $this->setBody("
        <p>Увага!</p>
        <p>Відбулося сканування QR-коду жетону вашої тварини, що було зафіксовано в Реєстрі домашніх тварин</p>
        ");
    }
}
