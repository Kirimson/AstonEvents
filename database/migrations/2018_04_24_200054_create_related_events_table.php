<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_events', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('event_id')->unsigned();
	        $table->integer('related_event_id')->unsigned();
	        $table->foreign('event_id')->references('id')->on('events');
	        $table->foreign('related_event_id')->references('id')->on('events');
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
        Schema::dropIfExists('related_events');
    }
}
