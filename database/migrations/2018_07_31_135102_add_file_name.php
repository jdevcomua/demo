<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals_files', function (Blueprint $table) {
            $table->string('name', 120)->after('num');
            $table->string('path')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals_files', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('path', 120)->change();
        });
    }
}
