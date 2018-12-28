<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('email_template_id')->nullable();
            $table->string('type');
            $table->unsignedInteger('period_type')->nullable();
            $table->unsignedInteger('period')->nullable();
            $table->string('dates', 1000)->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('priority')->default('default');
            $table->dateTime('last_fired')->nullable();
            $table->timestamps();

            $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing_configs');
    }
}
