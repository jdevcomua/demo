<?php

use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('animals')->insert([
            [
                'species_id' => 1, 'breed_id' => 8, 'gender' => 0,
                'color_id' => 9, 'nickname' => 'Бобик', 'birthday' => \Carbon\Carbon::now()->subYears(3),
                'user_id' => 2, 'status' => 0, 'confirm_user_id' => null
            ],
            [
                'species_id' => 1, 'breed_id' => 8, 'gender' => 0,
                'color_id' => 9, 'nickname' => 'Шарик', 'birthday' => \Carbon\Carbon::now()->subYears(3),
                'user_id' => 2, 'status' => 1, 'confirm_user_id' => 1
            ],
        ]);
    }
}
