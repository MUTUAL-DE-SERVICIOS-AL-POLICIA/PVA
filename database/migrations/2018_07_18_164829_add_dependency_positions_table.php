<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDependencyPositionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dependency_positions', function (Blueprint $table) {
      $table->integer('superior_id')->unsigned();
      $table->foreign('superior_id')->references('id')->on('positions');
      $table->integer('dependent_id')->unsigned();
      $table->foreign('dependent_id')->references('id')->on('positions');
      $table->boolean('active')->default(true);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('dependency_positions');
  }
}
