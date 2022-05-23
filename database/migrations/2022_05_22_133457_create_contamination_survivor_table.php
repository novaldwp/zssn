<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContaminationSurvivorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contamination_survivors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("survivor_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("report_by")->constrained("survivors")->onUpdate("cascade")->onDelete("cascade");
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
        Schema::dropIfExists('contamination_survivors');
    }
}
