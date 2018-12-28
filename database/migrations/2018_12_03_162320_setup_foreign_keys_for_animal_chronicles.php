<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupForeignKeysForAnimalChronicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animal_chronicle_fields', function (Blueprint $table) {
            $table->foreign('animal_chronicle_type_id')
                ->references('id')
                ->on('animal_chronicle_types')
                ->onDelete('cascade');
        });

        Schema::table('animal_chronicles', function (Blueprint $table) {
            $table->foreign('animal_id')
                ->references('id')
                ->on('animals')
                ->onDelete('cascade');

            $table->foreign('animal_chronicle_type_id')
                ->references('id')
                ->on('animal_chronicle_types')
                ->onDelete('cascade');
        });

        Schema::table('animal_chronicle_field_values', function (Blueprint $table) {
            $table->foreign('animal_chronicle_field_id')
                ->references('id')
                ->on('animal_chronicle_fields')
                ->onDelete('cascade');

            $table->foreign('animal_chronicle_id')
                ->references('id')
                ->on('animal_chronicles')
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
        Schema::table('animal_chronicle_fields', function (Blueprint $table) {
            $table->dropForeign(['animal_chronicle_type_id']);
        });

        Schema::table('animal_chronicles', function (Blueprint $table) {
            $table->dropForeign(['animal_id']);
        });

        Schema::table('animal_chronicles', function (Blueprint $table) {
            $table->dropForeign(['animal_chronicle_type_id']);
        });

        Schema::table('animal_chronicle_field_values', function (Blueprint $table) {
            $table->dropForeign(['animal_chronicle_field_id']);
        });

        Schema::table('animal_chronicle_field_values', function (Blueprint $table) {
            $table->dropForeign(['animal_chronicle_id']);
        });
    }
}
