<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoundAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('found_animals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('species_id');
            $table->unsignedInteger('breed_id');
            $table->unsignedInteger('color_id');
            $table->string('badge')->nullable();
            $table->string('found_address');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('found_animals');
    }
}
