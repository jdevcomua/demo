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
        $this->addType('badge-added', 'Жетон з QR-кодом додано. Номер жетону: {badge}.', [
            'badge'
        ]);

        $this->addType('clip-added', 'Кліпсу додано. Номер кліпси: {clip}.', [
            'clip'
        ]);

        $this->addType('chip-added', 'Чіп додано. Номер чіпу: {chip}.', [
            'chip'
        ]);

        $this->addType('badge-removed', 'Жетон з QR-кодом видалено.', []);
        $this->addType('clip-removed', 'Кліпсу видалено.', []);
        $this->addType('chip-removed', 'Чіп видалено.', []);

        $this->addType('sterilization-added', 'Тварину було стерилізовано. Дата стерилізації: {date}.', [
            'date'
        ]);

        $this->addType('vaccination-added', 'Проведено щеплення від сказу. Дата проведення: {date}.', [
            'date'
        ]);

        $this->addType('veterinary-measure-added', 'Проведено ветеринарний захід: {veterinary_measure}. Дата проведення: {date}.', [
            'veterinary_measure', 'date'
        ]);

        $this->addType('animal-offense-added', '{offense_affiliation}. Вид правопорушення: {offense}. Дата: {date}.', [
            'date', 'offense_affiliation', 'offense'
        ]);

        $this->addType('verification-added', 'Тварину верифіковано.', []);
        $this->addType('verification-removed', 'Верифікацію відмінено.', []);
    }


    private function addType($type, $text, $fields)
    {
        $typeModel = new AnimalChronicleType();
        $fieldModel = new AnimalChronicleField();

        $typeM = $typeModel->where('type', '=', $type)->first();
        if (!$typeM) {
            $typeM = $typeModel->create([
                'type' => $type,
                'template_text' => $text
            ]);
        }

        foreach ($fields as $field) {
            $fieldM = $fieldModel
                ->where('animal_chronicle_type_id', '=', $typeM->id)
                ->where('field_name', '=', $field)
                ->first();

            if (!$fieldM) {
                $fieldModel->create([
                    'animal_chronicle_type_id' => $typeM->id,
                    'field_name' => $field
                ]);
            }
        }
    }
}
