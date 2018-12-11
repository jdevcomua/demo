<?php

namespace App\External;


use App\Models\Animal;
use App\User;

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

        $resultData = [
            'statusDate' => \Carbon\Carbon::now()->format('Y-m-d'),
            'description' => [
                'ua' => "Додано тваринку: " . $this->animalData()['nickname'],
            ],
            'details' => $mergedData
        ];
        return $resultData;
    }


    private function userData(): array
    {
        $userData = [
                'user_id' => $this->user->ext_id
        ];

        return $userData;
    }


    private function animalData(): array
    {
        $animalData = [
                'species' => $this->animal->species->name,
                'breed' => $this->animal->breed->name,
                'nickname' => $this->animal->nickname,
                'age' => $this->getAnimalAgeDataArray(),
                'verified' => $this->animal->verified,
                'verification_notification' => $this->animal->verified,
                'identifying_devices' => $this->getAnimalIdentifyingDevicesDataArray(),
                'animal_requests' => null
        ];

        return $animalData;
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
        $ageDiffCarbon = $this->animal->birthday->diff(\Carbon\Carbon::now());
        $age = [
            'years' => intval($ageDiffCarbon->format('%y')),
            'months' => intval($ageDiffCarbon->format('%m')),
            'days' => intval($ageDiffCarbon->format('%d')),
        ];

        return $age;
    }

}