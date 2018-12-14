<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalVeterinaryMeasureFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_veterinary_measure_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('animal_veterinary_measure_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('animal_veterinary_measure_id', 'files_animal_veterinary_measure_id_foreign')
                ->references('id')
                ->on('animal_veterinary_measures')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_veterinary_measure_files', function (Blueprint $table) {
            $table->dropForeign(['animal_veterinary_measure_id']);
        });

        Schema::dropIfExists('animal_veterinary_measure_files');
    }
}
