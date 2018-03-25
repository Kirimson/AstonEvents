<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *INSERT INTO `events`
    VALUES(1, now(), now(), 'test', 'test description', now(), null, 1, 'contact details', 'venue name')
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255);
            $table->string('description', 255);
            $table->timestamp('time')->nullable();
            $table->string('picture', 191)->nullable();
            $table->integer('organiser_id');
            $table->string('contact', 255);
            $table->string('venue', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
