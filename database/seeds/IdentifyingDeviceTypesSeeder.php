<?php

use App\Models\IdentifyingDeviceType;
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
        $types = [
            'Чіп',
            'Кліпса',
            'Жетон',
            'Тавро'
        ];

        foreach ($types as $type) {
            IdentifyingDeviceType::firstOrCreate(['name' => $type]);
        }
    }
}
