<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesToIdentifyingDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->removeDevicesWithAnimalIdNull();

        Schema::table('identifying_devices', function (Blueprint $table) {
            $table->dropForeign(['animal_id']);
        });

        Schema::table('identifying_devices', function (Blueprint $table) {
            $table->foreign('animal_id')
                ->references('id')
                ->on('animals')
                ->onDelete('cascade');

            $table->unsignedInteger('animal_id')->nullable(false)->change();
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
            $table->dropForeign(['animal_id']);
        });

        Schema::table('identifying_devices', function (Blueprint $table) {
            $table->foreign('animal_id')
                ->references('id')
                ->on('animals')
                ->onDelete('SET NULL');

            $table->unsignedInteger('animal_id')->nullable(true)->change();
        });
    }

    private function removeDevicesWithAnimalIdNull()
    {
        \App\Models\IdentifyingDevice::whereNull('animal_id')->delete();
    }
}
