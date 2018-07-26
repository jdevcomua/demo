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
                'email' => 'admin@admin.com',
                'phone' => '+380951234567',
                'birthday' => \Carbon\Carbon::now()->subYears(25),
                'inn' => 1234567890,
                'passport' => 'МР123456',
                'address_living' => json_encode([
                    'country' => null,
                    'country_code' => "ua",
                    'state' => null,
                    'city' => "Київ",
                    'district' => "Київська",
                    'street' => "Карла Маркса",
                    'building' => "12",
                    'apartment' => null,
                    'postcode' => null,
                    'lat' => null,
                    'lon' => null,
                ]),
                'address_registration' => json_encode([
                    'country' => null,
                    'country_code' => "ua",
                    'state' => null,
                    'city' => "Київ",
                    'district' => "Київська",
                    'street' => "Карла Маркса",
                    'building' => "12",
                    'apartment' => null,
                    'postcode' => null,
                    'lat' => null,
                    'lon' => null,
                ]),
                'gender' => \App\User::GENDER_MALE,
            ],
            [
                'ext_id' => 123,
                'first_name' => 'QwertyF',
                'last_name' => 'QwertyL',
                'middle_name' => 'QwertyM',
                'email' => 'qwe@qwe.com',
                'phone' => '+380509876541',
                'birthday' => \Carbon\Carbon::now()->subYears(20),
                'inn' => 9876543210,
                'passport' => 'МР123456',
                'address_living' => json_encode([
                    'country' => null,
                    'country_code' => "ua",
                    'state' => null,
                    'city' => "Київ",
                    'district' => "Київська",
                    'street' => "Карла Маркса",
                    'building' => "12",
                    'apartment' => null,
                    'postcode' => null,
                    'lat' => null,
                    'lon' => null,
                ]),
                'address_registration' => json_encode([
                    'country' => null,
                    'country_code' => "ua",
                    'state' => null,
                    'city' => "Київ",
                    'district' => "Київська",
                    'street' => "Мостова",
                    'building' => "34",
                    'apartment' => null,
                    'postcode' => null,
                    'lat' => null,
                    'lon' => null,
                ]),
                'gender' => \App\User::GENDER_MALE,
            ],
        ]);

        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1
            ]
        ]);
    }
}
