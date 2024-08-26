<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsultantToDependencyPositionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('dependency_positions', function (Blueprint $table) {
      $table->integer('dependent_id')->nullable()->change();
      $table->integer('consultant_id')->unsigned()->nullable();
      $table->foreign('consultant_id')->references('id')->on('consultant_positions');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('dependency_positions', function (Blueprint $table) {
      $table->integer('dependent_id')->nullable()->change();
      $table->dropColumn('consultant_id');
    });
  }
}
