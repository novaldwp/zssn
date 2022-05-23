<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSurvivorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_survivors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("survivor_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("item_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->smallInteger("amount");
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
        Schema::dropIfExists('item_survivors');
    }
}
