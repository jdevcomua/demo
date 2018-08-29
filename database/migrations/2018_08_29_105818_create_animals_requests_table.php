<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('breed_id')->nullable();
            $table->unsignedInteger('animal_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('fur_id')->nullable();
            $table->unsignedInteger('species_id')->nullable();
            $table->date('birthday')->nullable();
            $table->string('street')->nullable();
            $table->string('building')->nullable();
            $table->string('apartment')->nullable();
            $table->string('nickname')->nullable();
            $table->boolean('processed')->default(0);
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
        Schema::dropIfExists('animals_requests');
    }
}
