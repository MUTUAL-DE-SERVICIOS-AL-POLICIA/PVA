<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinMaxToJobSchedulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('job_schedules', function (Blueprint $table) {
      $table->tinyInteger('start_hour_min_limit')->unsigned()->nullable();
      $table->tinyInteger('start_minutes_min_limit')->unsigned()->nullable();
      $table->tinyInteger('end_hour_max_limit')->unsigned()->nullable();
      $table->tinyInteger('end_minutes_max_limit')->unsigned()->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('job_schedules', function (Blueprint $table) {
      $table->dropColumn(['start_hour_min_limit', 'start_minutes_min_limit', 'end_hour_max_limit', 'end_minutes_max_limit']);
    });
  }
}
