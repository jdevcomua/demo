<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'ext_user_id' => '000', 'type_registration_id' => '0', 'first_name' => 'AdminF',
                'last_name' => 'AdminL', 'middle_name' => 'AdminM',
                'birthday' => \Carbon\Carbon::now()->subYears(25), 'gender' => 0
            ],
            [
                'ext_user_id' => '000', 'type_registration_id' => '0', 'first_name' => 'QwertyF',
                'last_name' => 'QwertyL', 'middle_name' => 'QwertyM',
                'birthday' => \Carbon\Carbon::now()->subYears(25), 'gender' => 0
            ],
        ]);
    }
}
