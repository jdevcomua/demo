<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('species_id');
            $table->unsignedInteger('breed_id');
            $table->timestamp('date_of_birth')->nullable();
            $table->unsignedTinyInteger('gender');
            $table->unsignedInteger('color_id');
            $table->boolean('sterilized')->default(false);
            $table->string('nickname', 256);
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('status');
            $table->jsonb('data')->nullable();
            $table->string('number', 256)->nullable();
            $table->unsignedInteger('confirm_user_id')->nullable();
            $table->timestamps();

            $table->foreign('species_id')
                ->references('id')
                ->on('species')
                ->onDelete('cascade');

            $table->foreign('breed_id')
                ->references('id')
                ->on('breeds')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('confirm_user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('animals');
    }
}
