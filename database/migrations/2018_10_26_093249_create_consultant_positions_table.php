<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultantPositionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('consultant_positions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->integer('charge_id')->unsigned();
      $table->foreign('charge_id')->references('id')->on('charges');
      $table->integer('position_group_id')->unsigned();
      $table->foreign('position_group_id')->references('id')->on('position_groups');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('consultant_positions');
  }
}
