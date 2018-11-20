<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathArchiveRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_archive_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cause_of_death_id');
            $table->timestamp('died_at');
            $table->timestamps();

            $table->foreign('cause_of_death_id')
                ->references('id')
                ->on('cause_of_deaths')
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
        Schema::dropIfExists('death_archive_records');
    }
}
