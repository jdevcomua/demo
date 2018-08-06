<?php

use Illuminate\Database\Seeder;

class FursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('furs')->insert([
            ['name' => 'Без шерсті', 'species_id' => 1],
            ['name' => 'Гладкошерста', 'species_id' => 1],
            ['name' => 'Короткошерста', 'species_id' => 1],
            ['name' => 'Довгошерста', 'species_id' => 1],
            ['name' => 'Жорсткошерста', 'species_id' => 1],
            ['name' => 'Кучерява', 'species_id' => 1],

            ['name' => 'Без шерсті', 'species_id' => 2],
            ['name' => 'Гладкошерста', 'species_id' => 2],
            ['name' => 'Короткошерста', 'species_id' => 2],
            ['name' => 'Довгошерста', 'species_id' => 2],
            ['name' => 'Жорсткошерста', 'species_id' => 2],
            ['name' => 'Кучерява', 'species_id' => 2],
        ]);
    }
}
