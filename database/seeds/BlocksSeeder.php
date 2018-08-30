<?php

use Illuminate\Database\Seeder;

class BlocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blocks')->insert([
            [
                'title' => 'about-page',
                'subject' => null,
                'body' => 'Текст сторінки \'Про проект\''
            ],
            [
                'title' => 'email.new-animal',
                'subject' => 'Верифікуйте вашого улюбленця',
                'body' => 'Текст повідомлення'
            ],
            [
                'title' => 'animal-verify',
                'subject' => null,
                'body' => '<p>
                                Для закінчення реєстрації тварини Вам необхідно звернутися до Служби обліку та реєстрації тварин, за адресою:
                            </p>
                            <p>
                                <b>м. Київ</b><br>
                                <b>проспект Відрадний, 61</b><br>
                                <b>т. 044-497-65-23.</b>
                            </p>
                            <p>
                                <b>При собі мати:</b><br>
                                - документ, що посвідчує особу<br>
                                - ветеринарний паспорт<br>
                                - тварину.
                            </p>
                            <p>
                                <b>Часи роботи:</b><br>
                                Пн-Чт 9:00-18:00 год.<br>
                                Пт 9:00-16:45 год.<br>
                                Обід 13:00-14:00 год.
                            </p>'
            ],
            [
                'title' => 'email.request-accepted',
                'subject' => 'Ваш запит на тваринку було схвалено!',
                'body' => 'Ваш запит на тваринку було схвалено!'
            ],
            [
                'title' => 'email.request-cancelled',
                'subject' => 'Ваш запит на тваринку було відхилено!',
                'body' => 'Ваш запит на тваринку було відхилено!'
            ],
        ]);
    }
}
