<?php

use Illuminate\Database\Seeder;

class BreedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('breeds')->insert([
            ['name' => 'АФЕНПІНЧЕР', 'fci' => 186, 'species_id' => 1],
            ['name' => 'АФГАНСЬКИЙ ХОРТ', 'fci' => 228, 'species_id' => 1],
            ['name' => 'ЕРДЕЛЬ-ТЕР\'ЄР', 'fci' => 7, 'species_id' => 1],
            ['name' => 'АКІТА', 'fci' => 255, 'species_id' => 1],
            ['name' => 'АЛЯСКИНСЬКИЙ МАЛАМУТ', 'fci' => 243, 'species_id' => 1],
            ['name' => 'АЛЬПІЙСЬКИЙ ДАКСБРАКЕ', 'fci' => 254, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ АКІТА', 'fci' => 344, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ БУЛДОҐ', 'fci' => 0, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ КОКЕР-СПАНІЄЛЬ', 'fci' => 167, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ ФОКСГАУНД', 'fci' => 303, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ ГОЛИЙ ТЕР\'ЄР', 'fci' => 0, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ СТАФОРДШИР-ТЕР\'ЄР', 'fci' => 286, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ ТОЙ-ФОКС-ТЕР\’ЄР', 'fci' => 0, 'species_id' => 1],
            ['name' => 'АМЕРИКАНСЬКИЙ ВОДЯНИЙ СПАНІЄЛЬ', 'fci' => 301, 'species_id' => 1],
            ['name' => 'АНАТОЛІЙСЬКИЙ ВІВЧАР', 'fci' => 331, 'species_id' => 1],
            ['name' => 'АППЕНЦЕЛЬСЬКИЙ СКОТАР', 'fci' => 46, 'species_id' => 1],
            ['name' => 'АР’ЄЗЬКИЙ ЛЯГАВИЙ СОБАКА', 'fci' => 177, 'species_id' => 1],
            ['name' => 'АР’ЄЗЬКИЙ ГОНЧАК', 'fci' => 20, 'species_id' => 1],
            ['name' => 'АРТУАЗЬКИЙ ГОНЧАК', 'fci' => 28, 'species_id' => 1],
            ['name' => 'АТЛАСНИЙ ГІРСЬКИЙ СОБАКА, АІДІ', 'fci' => 247, 'species_id' => 1],
            ['name' => 'АВСТРАЛІЙСЬКИЙ СКОТАР', 'fci' => 287, 'species_id' => 1]
        ]);
    }
}
