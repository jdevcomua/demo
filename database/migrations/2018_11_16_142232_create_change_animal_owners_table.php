<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeAnimalOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_animal_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('animals_requests_id');
            $table->unsignedInteger('animal_id');
            $table->string('passport', 15);
            $table->string('full_name', 256);
            $table->string('contact_phone', 20);
            $table->timestamps();

            $table->foreign('animals_requests_id')
                ->references('id')
                ->on('animals_requests')
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
        Schema::dropIfExists('change_animal_owners');
    }
}
