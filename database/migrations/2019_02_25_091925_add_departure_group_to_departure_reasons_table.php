<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartureGroupToDepartureReasonsTable extends Migration
{
  /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::table('departure_reasons', function (Blueprint $table) {
      $table->integer('departure_group_id')->nullable();
      $table->foreign('departure_group_id')->references('id')->on('departure_groups');
    });
  }

  /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
  {
    Schema::table('departure_reasons', function (Blueprint $table) {
      $table->dropColumn(['departure_group_id']);
    });
  }
}
