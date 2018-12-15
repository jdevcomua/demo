<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalOffenseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_offense_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('animal_offense_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('animal_offense_id')
                ->references('id')
                ->on('animal_offenses')
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
        Schema::dropIfExists('animal_offense_files');
    }
}
