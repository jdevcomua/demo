<?php

namespace App\External;

use App\Models\Animal;
use App\User;
use Carbon\Carbon;

class AnimalAddedOrder implements OrderInterface
{
    protected $user;
    protected $animal;

    protected $data;

    public function __construct(User $user, Animal $animal)
    {
        $this->user = $user;
        $this->animal = $animal;
        $this->data = $this->data();
    }

    public function dataAsJson()
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    public function dataAsArray()
    {
        return $this->data;
    }

    private function data(): array
    {
        $mergedData = array_merge($this->userData(), $this->animalData());

        return [
            'statusDate' => Carbon::now()->format('Y-m-d'),
            'description' => [
                'ua' => "Додано тваринку: " . $this->animalData()['nickname'],
            ],
            'details' => $mergedData
        ];
    }

    private function userData(): array
    {
        return [
            'user_id' => $this->user->ext_id
        ];
    }

    private function animalData(): array
    {
        return [
            'species' => $this->animal->species->name,
            'breed' => $this->animal->breed->name,
            'nickname' => $this->animal->nickname,
            'age' => $this->getAnimalAgeDataArray(),
            'verified' => $this->animal->verified,
            'verification_notification' => $this->animal->verified,
            'identifying_devices' => $this->getAnimalIdentifyingDevicesDataArray(),
            'animal_requests' => null
        ];
    }

    private function getAnimalIdentifyingDevicesDataArray(): ?array
    {
        $identifying_devices = [];

        if ($this->animal->identifying_devices_count) {
            foreach ($this->animal->identifyingDevicesArray() as $k => $v) {
                if ($this->animal->$k !== null) {
                    $identifying_devices[] = [
                        'type' => $v,
                        'number' => $this->animal->$k
                    ];
                }
            }
        }
        return  count($identifying_devices) ? $identifying_devices : null;
    }

    private function getAnimalAgeDataArray(): array
    {
        $age = $this->animal->birthday->diff(new Carbon);

        return [
            'years' => $age->y,
            'months' => $age->m,
            'days' => $age->d,
        ];
    }
}
