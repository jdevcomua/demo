<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BreedColorNotRequiredOnFound extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('found_animals', function (Blueprint $table) {
            $table->unsignedInteger('breed_id')->nullable()->change();
            $table->unsignedInteger('color_id')->nullable()->change();
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
            $table->unsignedInteger('breed_id')->nullable(false)->change();
            $table->unsignedInteger('color_id')->nullable(false)->change();
        });
    }
}
