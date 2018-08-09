<?php

use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            \App\Models\Notification::TYPE_NOT_VERIFIED,
        ];

        foreach ($types as $type) {
            $notification = new \App\Models\Notification();
            $notification->type = $type;
            $notification->min = 1;
            $notification->max = 1;
            $notification->text = 'текст для 1 тварини';
            $notification->save();

            $notificationSome = new \App\Models\Notification();
            $notificationSome->type = $type;
            $notificationSome->min = 2;
            $notificationSome->max = 4;
            $notificationSome->text = 'текст для декількох тварин';
            $notificationSome->save();

            $notificationMany = new \App\Models\Notification();
            $notificationMany->type = $type;
            $notificationMany->min = 5;
            $notificationMany->max = 1000;
            $notificationMany->text = 'текст для багатьох тварин';
            $notificationMany->save();
        }
    }
}
