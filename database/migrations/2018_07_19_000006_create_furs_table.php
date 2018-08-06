<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->unsignedSmallInteger('species_id');
            $table->boolean('available')->default(true);
//            $table->timestamps();

            $table->foreign('species_id')
                ->references('id')
                ->on('species')
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
        Schema::dropIfExists('furs');
    }
}
