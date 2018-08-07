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
        ]);
    }
}
