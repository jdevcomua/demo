<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedColumnToFoundAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('found_animals', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
            $table->boolean('processed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('found_animals', function (Blueprint $table) {
            $table->dropColumn('approved');
            $table->dropColumn('processed');
        });
    }
}
