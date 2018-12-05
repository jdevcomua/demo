<?php

use App\Models\AnimalChronicleField;
use App\Models\AnimalChronicleType;
use Illuminate\Database\Seeder;

class AnimalChroniclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'badge-added';
        $chronicleType->template_text = 'Жетон з QR-кодом додано. Номер жетону: {badge}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = 'badge';
        $chronicleField->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'clip-added';
        $chronicleType->template_text = 'Кліпсу додано. Номер кліпси: {clip}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = 'clip';
        $chronicleField->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'chip-added';
        $chronicleType->template_text = 'Чіп додано. Номер чіпу: {chip}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = 'chip';
        $chronicleField->save();


        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'badge-removed';
        $chronicleType->template_text = 'Жетон з QR-кодом видалено.';
        $chronicleType->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'clip-removed';
        $chronicleType->template_text = 'Кліпсу видалено.';
        $chronicleType->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = 'chip-removed';
        $chronicleType->template_text = 'Чіп видалено.';
        $chronicleType->save();

    }
}
