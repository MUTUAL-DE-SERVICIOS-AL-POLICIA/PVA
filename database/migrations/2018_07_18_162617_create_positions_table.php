<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('positions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->integer('item');
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
    Schema::dropIfExists('positions');
  }
}
