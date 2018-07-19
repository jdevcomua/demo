<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SpeciesSeeder::class);
        $this->call(ColorsSeeder::class);
        $this->call(BreedsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AnimalSeeder::class);
    }
}
