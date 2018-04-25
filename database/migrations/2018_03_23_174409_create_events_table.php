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
	        $table->integer('organiser_id')->unsigned();
	        $table->string('name', 191)->unique();
	        $table->longtext('description');
	        $table->enum('category', array('sport', 'culture', 'other'));
	        $table->timestamp('time')->nullable();
	        $table->string('picture', 191)->nullable();
	        $table->string('contact', 191);
	        $table->string('venue', 191);
	        $table->integer('likes')->default(0);
	        $table->timestamps();
	        $table->foreign('organiser_id')->references('id')->on('users');
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
