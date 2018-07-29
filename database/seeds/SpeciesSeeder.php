<?php

use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('species')->insert([
            ['name' => 'Собака'],
            ['name' => 'Кiт']
        ]);
    }
}
