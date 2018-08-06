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
            $table->string('nickname', 256);
            $table->unsignedSmallInteger('species_id');
            $table->unsignedInteger('breed_id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('fur_id');
            $table->unsignedTinyInteger('gender');
            $table->date('birthday');
            $table->boolean('sterilized')->default(false);
            $table->unsignedInteger('user_id');
            $table->boolean('verified')->default(false);
            $table->text('comment')->nullable();
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

            $table->foreign('fur_id')
                ->references('id')
                ->on('furs')
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
