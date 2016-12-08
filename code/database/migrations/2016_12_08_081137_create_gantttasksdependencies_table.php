<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGantttasksdependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GanttTaksDependencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('predecessor_id');
            $table->integer('successor_id');
            $table->integer('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GanttTaksDependencies');
    }
}
