<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistantContractsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('assistant_contracts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('employee_id')->unsigned();
      $table->foreign('employee_id')->references('id')->on('employees');
      $table->string('university')->unsigned();
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->enum('assistant_position', ['Pasantía', 'Trabajo Dirigido'])->default('Pasantía');
      $table->integer('position_group_id')->unsigned();
      $table->foreign('position_group_id')->references('id')->on('position_groups');
      $table->date('retirement_date')->nullable();
      $table->integer('retirement_reason_id')->nullable();
      $table->foreign('retirement_reason_id')->references('id')->on('retirement_reasons');
      $table->string('register_number')->nullable();;
      $table->text('description')->nullable();
      $table->boolean('active')->default(true);
      $table->integer('job_schedule_id')->unsigned()->nullable();
      $table->foreign('job_schedule_id')->references('id')->on('job_schedules');
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
    Schema::dropIfExists('consultant_contracts');
  }
}
