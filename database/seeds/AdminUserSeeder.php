<?php

use Illuminate\Database\Seeder;

/**
 * Class AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            factory(App\User::class)->create([
                    "name" => env('ADMIN_USER', "Admin"),
                    "email" => env('ADMIN_EMAIL', "admin@admin.com"),
                    "password" => bcrypt('qweqwe')]);
        } catch (\Illuminate\Database\QueryException $exception) {
        }
    }
}
