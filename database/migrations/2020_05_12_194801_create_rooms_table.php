<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('room_number');
            $table->enum('room_type',['single','double','twin','delux','queen' , 'king']);
            $table->integer('floor_no');
            $table->float('rent');
            $table->boolean('smoking_zone')->default(false);
            $table->boolean('reservation_status')->default(ROOM_RESERVATION_ACTIVE_STATUS);
            $table->dateTime('available_at');
            $table->bigInteger('hotel_id')->unsigned();
            $table->timestamps();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
