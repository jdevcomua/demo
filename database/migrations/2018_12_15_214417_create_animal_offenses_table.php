<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalOffensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_offenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('offense_id');
            $table->unsignedInteger('offense_affiliation_id');
            $table->unsignedInteger('animal_id');
            $table->date('date');
            $table->date('protocol_date');
            $table->string('protocol_number');
            $table->string('description')->nullable();
            $table->boolean('bite');
            $table->string('made_by');
            $table->timestamps();

            $table->foreign('offense_id')
                ->references('id')
                ->on('offenses')
                ->onDelete('cascade');

            $table->foreign('offense_affiliation_id')
                ->references('id')
                ->on('offense_affiliations')
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
        Schema::dropIfExists('animal_offenses');
    }
}
