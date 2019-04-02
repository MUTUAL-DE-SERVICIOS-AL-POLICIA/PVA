<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectorToDeparturesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('departures', function (Blueprint $table) {
      $table->string('cite')->unique()->nullable();
      $table->integer('director_id')->nullable();
      $table->foreign('director_id')->references('id')->on('employees');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('departures', function (Blueprint $table) {
      $table->dropColumn(['cite', 'director_id']);
    });
  }
}
