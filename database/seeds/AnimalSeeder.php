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
//        DB::table('animals')->insert([
//            [
//                'species_id' => 1, 'breed_id' => 8, 'gender' => 0, 'color_id' => 9,
//                'fur_id' => 2, 'nickname' => 'Бобик', 'birthday' => \Carbon\Carbon::now()->subYears(3),
//                'user_id' => 1, 'verified' => 0, 'confirm_user_id' => null
//            ],
//            [
//                'species_id' => 1, 'breed_id' => 8, 'gender' => 0, 'color_id' => 8,
//                'fur_id' => 4, 'nickname' => 'Шарик', 'birthday' => \Carbon\Carbon::now()->subYears(3),
//                'user_id' => 1, 'verified' => 0, 'confirm_user_id' => 1
//            ],
//        ]);
        for ($i = 1; $i < 1000; $i++) {
            DB::table('animals')->insert([
                [
                    'id' => $i,
                    'species_id' => rand(1,2), 'breed_id' => rand(1, 21), 'gender' => 0, 'color_id' => rand(1, 29),
                    'fur_id' => rand(1,12), 'nickname' => str_random(16), 'birthday' => \Carbon\Carbon::now()->subYears(3),
                    'user_id' => 1, 'verified' => 1, 'confirm_user_id' => 1
                ]
            ]);
            DB::table('lost_animals')->insert([
             [
                 'animal_id' => $i,
                 'found' => rand(0,1)
             ]
            ]);
        }


    }
}
