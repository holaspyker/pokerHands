<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersHands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hands', function (Blueprint $table) {
            $table->id();
            $table->string("c0");
            $table->string("c1");
            $table->string("c2");
            $table->string("c3");
            $table->string("c4");
            $table->integer("player");
            $table->integer("round_id");
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
        Schema::dropIfExists('hands');
    }
}
