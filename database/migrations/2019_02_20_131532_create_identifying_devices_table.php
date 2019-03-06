<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentifyingDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identifying_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('identifying_device_type_id');
            $table->unsignedInteger('animal_id')->nullable();
            $table->string('number');
            $table->string('issued_by');
            $table->string('info')->nullable();
            $table->timestamps();

            $table->foreign('identifying_device_type_id')
                ->references('id')
                ->on('identifying_device_types')
                ->onDelete('cascade');

            $table->foreign('animal_id')
                ->references('id')
                ->on('animals')
                ->onDelete('SET NULL');

            $table->unique(['identifying_device_type_id', 'animal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('identifying_devices', function (Blueprint $table) {
            $table->dropForeign(['identifying_device_type_id']);
            $table->dropForeign(['animal_id']);
            $table->dropUnique(['identifying_device_type_id', 'animal_id']);
        });

        Schema::dropIfExists('identifying_devices');
    }
}
