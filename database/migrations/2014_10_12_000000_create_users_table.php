<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ext_id');
            $table->string('first_name', 256);
            $table->string('last_name', 256);
            $table->string('middle_name', 256)->nullable();
            $table->string('email', 256);
            $table->string('phone', 20)->nullable();
            $table->dateTime('birthday');
            $table->string('inn', 15);
            $table->string('passport', 15);
            $table->json('address_living')->nullable();
            $table->json('address_registration')->nullable();
            $table->unsignedTinyInteger('gender');
            $table->dateTime('banned_at')->nullable();
            $table->integer('banned_by')->unsigned()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
