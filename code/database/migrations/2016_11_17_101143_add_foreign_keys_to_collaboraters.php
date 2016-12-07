<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCollaboraters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('collaboraters', function (Blueprint $table) {
          /*$table->increments('id');
          $table->integer('user_id')->unsigned()->index();
          $table->integer('project_id')->unsigned()->index();
          $table->integer('informations_rights');
          $table->integer('gantt_rights');
          $table->integer('budget_rights');
          $table->timestamps();*/

           $table->foreign('user_id')->references('id')->on('users');
           $table->foreign('project_id')->references('id')->on('projects');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
