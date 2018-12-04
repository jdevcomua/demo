<?php

use App\Models\AnimalChronicle;
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
        $chronicleType->type = AnimalChronicle::TYPE_ADDED_BADGE;
        $chronicleType->template_text = 'Жетон з QR-кодом додано. Номер жетону: {' . AnimalChronicle::FIELD_BADGE . '}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = AnimalChronicle::FIELD_BADGE;
        $chronicleField->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = AnimalChronicle::TYPE_ADDED_CLIP;
        $chronicleType->template_text = 'Кліпсу додано. Номер кліпси: {' . AnimalChronicle::FIELD_CLIP . '}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = AnimalChronicle::FIELD_CLIP;
        $chronicleField->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = AnimalChronicle::TYPE_ADDED_CHIP;
        $chronicleType->template_text = 'Чіп додано. Номер чіпу: {' . AnimalChronicle::FIELD_CHIP . '}.';
        $chronicleType->save();

        $chronicleField = new AnimalChronicleField;
        $chronicleField->animal_chronicle_type_id = $chronicleType->id;
        $chronicleField->field_name = AnimalChronicle::FIELD_CLIP;
        $chronicleField->save();


        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = AnimalChronicle::TYPE_REMOVED_BADGE;
        $chronicleType->template_text = 'Жетон з QR-кодом видалено.';
        $chronicleType->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = AnimalChronicle::TYPE_REMOVED_CLIP;
        $chronicleType->template_text = 'Кліпсу видалено.';
        $chronicleType->save();

        $chronicleType = new AnimalChronicleType;
        $chronicleType->type = AnimalChronicle::TYPE_REMOVED_CHIP;
        $chronicleType->template_text = 'Чіп видалено.';
        $chronicleType->save();

    }
}
