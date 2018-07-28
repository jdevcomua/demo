<?php

use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('colors')->insert([
            ['name' => 'Абрикосовий', 'species_id' => 1],
            ['name' => 'перлинно-білий', 'species_id' => 1],
            ['name' => 'Білий', 'species_id' => 1],
            ['name' => 'Блакитний', 'species_id' => 1],
            ['name' => 'Інші кольори', 'species_id' => 1],
            ['name' => 'Коричневий', 'species_id' => 1],
            ['name' => 'Мармуровий', 'species_id' => 1],
            ['name' => 'Палевий', 'species_id' => 1],
            ['name' => 'Перець з сіллю', 'species_id' => 1],
            ['name' => 'Плямистий чорно-білий', 'species_id' => 1],
            ['name' => 'Помаранчевий', 'species_id' => 1],
            ['name' => 'Рудий', 'species_id' => 1],
            ['name' => 'Сірий', 'species_id' => 1],
            ['name' => 'Сріблястий', 'species_id' => 1],
            ['name' => 'Тигровий', 'species_id' => 1],
            ['name' => 'Червоно-палевий', 'species_id' => 1],
            ['name' => 'Чорний', 'species_id' => 1],
            ['name' => 'Чорний з підпалинами', 'species_id' => 1],
            ['name' => 'Чорний зі сріблом', 'species_id' => 1],
            ['name' => 'Лиловый', 'species_id' => 1],
            ['name' => 'Арлекін, Мармуровий', 'species_id' => 1],
            ['name' => 'Біла масть', 'species_id' => 1],
            ['name' => 'Золотисто-рудий', 'species_id' => 1],
            ['name' => 'Зонарно-бурий', 'species_id' => 1],
            ['name' => 'Зонарно-сірий, вовчий окрас', 'species_id' => 1],
            ['name' => 'Палевий', 'species_id' => 1],
            ['name' => 'Плямистий', 'species_id' => 1],
            ['name' => 'Суцільний', 'species_id' => 1],
            ['name' => 'Чепрачний окрас', 'species_id' => 1]
        ]);
    }
}
