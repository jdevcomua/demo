<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBadgeToAnimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign('animals_user_id_foreign');
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->string('badge')->nullable()->index();
            $table->unsignedInteger('request_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('badge');
            $table->dropColumn('request_user_id');
        });
    }
}
