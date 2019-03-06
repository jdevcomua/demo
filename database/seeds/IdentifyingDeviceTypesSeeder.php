<?php

use Illuminate\Database\Seeder;

class IdentifyingDeviceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('identifying_device_types')->insert([
            ['name' => 'Чіп'],
            ['name' => 'Кліпса'],
            ['name' => 'Жетон'],
            ['name' => 'Тавро']
        ]);
    }
}
