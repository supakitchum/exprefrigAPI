<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_factories', function (Blueprint $table) {
            $table->string('private_key',10);
            $table->primary('private_key');
            $table->enum('activated',['yes','no'])->default('no');
            $table->integer('own')->nullable();
            $table->text('chip_id')->nullable();
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
        Schema::dropIfExists('board_factories');
    }
}
