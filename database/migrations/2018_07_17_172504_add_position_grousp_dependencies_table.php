<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionGrouspDependenciesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dependency_position_group', function (Blueprint $table) {
      $table->integer('superior_id')->unsigned();
      $table->foreign('superior_id')->references('id')->on('position_groups');
      $table->integer('dependent_id')->unsigned();
      $table->foreign('dependent_id')->references('id')->on('position_groups');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('dependency_position_group');
  }
}
