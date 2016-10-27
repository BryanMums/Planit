<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_mail')->nullable();
            $table->string('client_tel', 15)->nullable();
            $table->float('budget')->nullable();
            $table->date('date_begin')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('user_id')->unsigned()->index();
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
        Schema::dropIfExists('projects');
    }
}
