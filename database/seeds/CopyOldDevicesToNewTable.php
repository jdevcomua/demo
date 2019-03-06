<?php

use App\Models\Animal;
use Illuminate\Database\Seeder;

class CopyOldDevicesToNewTable extends Seeder
{
    const ISSUED_BY = 'Адміністратор системи';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $animals = Animal::all();
        foreach ($animals as $animal) {
            $existingDevices = $this->getDevicesArray($animal);
            foreach ($existingDevices as $typeName => $number) {
                $this->addDevice($animal, $typeName, $number);
            }
        }
    }

    private function getDevicesArray ($animal)
    {
        $devicesArray = [];
        if ($animal->badge !== null) {
            $devicesArray['Жетон'] = $animal->badge;
        }

        if ($animal->chip !== null) {
            $devicesArray['Чіп'] = $animal->badge;
        }

        if ($animal->clip !== null) {
            $devicesArray['Кліпса'] = $animal->badge;
        }
        return $devicesArray;
    }

    private function addDevice($animal, $typeName, $number)
    {
        $deviceType = \App\Models\IdentifyingDeviceType::where('name', $typeName)->firstOrFail();
        $deviceTypeId = $deviceType->id;
        if (!$this->alreadyExists($animal, $deviceTypeId)) {
            $animal->identifyingDevices()->create([
                'number' => $number,
                'identifying_device_type_id' => $deviceTypeId,
                'issued_by' => self::ISSUED_BY
            ]);
            $this->printMsg("Successfully added device " . $typeName . " to Animal #" . $animal->id);
        } else {
            $this->printMsg("Animal #" . $animal->id . " already has device with type of " . $typeName);
            $this->printMsg("Omitting..");
        }
    }

    private function alreadyExists($animal, $deviceTypeId):bool
    {
        $exists = false;
        foreach ($animal->identifyingDevices as $device) {
            if ($device->type->id === $deviceTypeId) {
                return true;
            }
        }
        return $exists;
    }

    private function printMsg($msg): void
    {
        echo $msg . "\n";
    }
}
