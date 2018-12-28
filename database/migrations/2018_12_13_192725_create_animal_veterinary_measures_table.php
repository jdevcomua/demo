<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalVeterinaryMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_veterinary_measures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('veterinary_measure_id');
            $table->unsignedInteger('animal_id');
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('made_by');
            $table->timestamps();

            $table->foreign('veterinary_measure_id')
                ->references('id')
                ->on('veterinary_measures')
                ->onDelete('cascade');

            $table->foreign('animal_id')
                ->references('id')
                ->on('animals')
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
        Schema::dropIfExists('animal_veterinary_measures');
    }
}
