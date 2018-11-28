<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoundAnimalsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('found_animals_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('found_animal_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('found_animal_id')
                ->references('id')
                ->on('found_animals')
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
        Schema::dropIfExists('found_animals_files');
    }
}
