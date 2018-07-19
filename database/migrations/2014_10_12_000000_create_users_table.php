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
            $table->string('ext_user_id', 256);
            $table->integer('type_registration_id');
            $table->string('first_name', 256);
            $table->string('second_name', 256);
            $table->string('middle_name', 256)->nullable();
            $table->string('email', 256)->nullable();
            $table->string('phone', 15)->nullable();
            $table->timestamp('birthday');
            $table->unsignedTinyInteger('gender');
            $table->jsonb('data');
            $table->string('passport', 20)->nullable();
            $table->text('residence_address')->nullable();
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
