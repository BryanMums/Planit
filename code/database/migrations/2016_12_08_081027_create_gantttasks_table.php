<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGantttasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GanttTasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('title', 30);
            $table->text('description')->nullable();
            $table->date('date_begin_plan');
            $table->integer('duration_plan');
            $table->integer('hours_plan');
            $table->date('date_begin_real')->nullable();
            $table->integer('duration_real')->nullable();
            $table->integer('hours_real')->nullable();
            $table->integer('percent_done')->unsigned()->default(0);
            $table->string('color', 10);
            $table->integer('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GanttTasks');
    }
}
