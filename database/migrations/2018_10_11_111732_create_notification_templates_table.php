<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notifications_content');

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type');
            $table->string('name')->unique();
            $table->text('subject')->nullable();
            $table->text('body');
            $table->boolean('active')->default(true);
            $table->text('events')->nullable();
            $table->timestamps();
        });

        DB::table('notification_templates')->insert([
            'type' => \App\Models\NotificationTemplate::TYPE_SYSTEM,
            'name' => 'animal-verify',
            'body' => 'Верифікуйте {user.animals_unverified.count} тваринки',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_templates');

        Schema::create('notifications_content', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('min');
            $table->string('max');
            $table->string('text');
            $table->timestamps();
        });
    }
}
