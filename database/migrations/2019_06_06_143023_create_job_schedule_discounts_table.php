<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobScheduleDiscountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('job_schedule_discounts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->tinyInteger('limit')->unsigned();
      $table->tinyInteger('time')->unsigned();
      $table->enum('unit', ['minutes', 'hours', 'days']);
      $table->decimal('discount', 3, 2)->unsigned();
      $table->boolean('active')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('job_schedule_discounts');
  }
}
