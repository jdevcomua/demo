<?php

namespace App\Events;


class AnimalFoundCreated extends SendEmailEvent
{
    public static $display_name = 'Створено повідомлення про знайдену тварину';

    public function __construct($email)
    {
        parent::__construct($email);

        $this->setSubject('Створено повідомлення про знайдену тварину');
        $this->setBody("
        <p>Повідомлення від Системи \"Реєстр домашніх тварин\"
        <a href='https://pets.kyivcity.gov.ua'>pets.kyivcity.gov.ua</a></p>
        <br>
        <p>Дякуємо!</p>
        <p>Повідомлення про знайдену тварину створено!</p>
        <br>
        <p>Ваше повідомлення з’явиться в розділі “Знайдені тварини” у вигляді оголошення про знайдену тварину після затвердження його Модератором.</p>
        <br>
        <p>Служба підтримки користувачів</p>
        <p>\"Реєстру домашніх тварин\"</p>
        <p>телефон: (044) 366-80-19</p>
        <p>e-mail: <a href='mailto:support.rdt@kyivcity.gov.ua'>support.rdt@kyivcity.gov.ua</a></p>
        ");
    }
}
