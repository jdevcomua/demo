<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesToFoundAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('found_animals', function (Blueprint $table) {
            $table->unsignedSmallInteger('species_id')->nullable()->change();
            $table->string('found_address')->nullable()->change();
            $table->string('contact_email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('found_animals', function (Blueprint $table) {
            $table->unsignedSmallInteger('species_id')->nullable(false)->change();
            $table->string('found_address')->nullable(false)->change();
            $table->string('contact_email')->nullable(false)->change();
        });
    }
}
