<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('locations', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->integer('phone_number')->nullable();
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
    Schema::dropIfExists('locations');
  }
}
