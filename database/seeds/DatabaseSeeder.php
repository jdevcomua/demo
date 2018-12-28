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
        $this->call(RolesPermissionsSeeder::class);

        if (config('app.debug')) {
            $this->call(SpeciesSeeder::class);
            $this->call(ColorsSeeder::class);
            $this->call(BreedsSeeder::class);
            $this->call(FursSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(AnimalSeeder::class);
        }

        $this->call(BlocksSeeder::class);
        $this->call(AnimalChroniclesSeeder::class);
//        $this->call(NotificationsSeeder::class);
    }
}
