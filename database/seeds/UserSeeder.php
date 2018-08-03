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
                'ext_id' => 111,
                'first_name' => 'AdminF',
                'last_name' => 'AdminL',
                'middle_name' => 'AdminM',
                'birthday' => \Carbon\Carbon::now()->subYears(25),
                'inn' => 1234567890,
                'passport' => 'МР123456',
                'gender' => \App\User::GENDER_MALE,
            ]]);
        DB::transaction(function () {
            for ($i = 2; $i < 1000; $i++)
                DB::table('users')->insert([[
                    'ext_id' => 123 + $i,
                    'first_name' => str_random(7),
                    'last_name' => str_random(7),
                    'middle_name' => str_random(7),
                    'birthday' => \Carbon\Carbon::now()->subYears(20 + rand(0, 20)),
                    'inn' => 1876543210 + $i,
                    'passport' => 'МР' . (123456 + $i),
                    'gender' => ($i % 2) ? \App\User::GENDER_MALE : \App\User::GENDER_FEMALE,
                ],
                ]);
        });
        DB::transaction(function () {
            for ($i = 1; $i < 1000; $i++)
                DB::table('user_addresses')->insert([[
                    'user_id' => $i,
                    'type' => 'REGISTRATION',
                    'country' => null,
                    'country_code' => "ua",
                    'state' => null,
                    'city' => "Київ",
                    'district' => "Київська",
                    'street' => ($i % 2) ? "Карла Маркса" : "Мостова",
                    'building' => ($i % 2) ? "12" : "34",
                    'apartment' => null,
                    'postcode' => null,
                    'lat' => null,
                    'lon' => null,
                ],
                ]);
        });
        DB::transaction(function () {
            for ($i = 1; $i < 1000; $i++)
                DB::table('user_emails')->insert([[
                    'user_id' => $i,
                    'type' => "PRIMARY",
                    'email' => str_random(14) . '@qwe.com',
                ],
                ]);
        });
        DB::transaction(function () {
            for ($i = 1; $i < 1000; $i++)
                DB::table('user_phones')->insert([[
                    'user_id' => $i,
                    'type' => "PRIMARY",
                    'phone' => '+38050' . (9876541 + $i),
                ],
                ]);
        });

        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1
            ]
        ]);
    }
}
