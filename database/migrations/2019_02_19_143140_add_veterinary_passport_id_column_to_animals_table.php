<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVeterinaryPassportIdColumnToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->unsignedInteger('veterinary_passport_id')->nullable();

            $table->foreign('veterinary_passport_id')
                ->references('id')
                ->on('veterinary_passports')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['veterinary_passport_id']);
            $table->dropColumn('veterinary_passport_id');
        });
    }
}
