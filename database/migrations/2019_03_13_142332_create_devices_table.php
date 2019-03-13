<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('private_key',10);
            $table->integer('uid')->nullable();
            $table->integer('refrig_id')->nullable();
            $table->index(['private_key','refrig_id','uid']);
            $table->string('name')->charset('utf8')->nullable();
            $table->dateTime('dateTime')->nullable();
            $table->dateTime('dateTimeYellow')->nullable();
            $table->string('image')->nullable();
//            $table->foreign('uid')->reference('uid')->on('members');
//            $table->foreign('private_key')->reference('private_key')->on('devices');
//            $table->foreign('refrig_id')->reference('refrig_id')->on('refrigerators');
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
        Schema::dropIfExists('devices');
    }
}
