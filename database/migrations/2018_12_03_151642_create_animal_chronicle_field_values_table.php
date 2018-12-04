<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalChronicleFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_chronicle_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field_value');
            $table->unsignedInteger('animal_chronicle_field_id');
            $table->unsignedInteger('animal_chronicle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_chronicle_field_values');
    }
}
