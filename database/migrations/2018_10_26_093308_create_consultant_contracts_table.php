<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultantContractsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('consultant_contracts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('employee_id')->unsigned();
      $table->foreign('employee_id')->references('id')->on('employees');
      $table->integer('consultant_position_id')->unsigned();
      $table->foreign('consultant_position_id')->references('id')->on('consultant_positions');
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->date('retirement_date')->nullable();
      $table->integer('retirement_reason_id')->nullable();
      $table->foreign('retirement_reason_id')->references('id')->on('retirement_reasons');
      $table->string('rrhh_cite')->nullable();
      $table->date('rrhh_cite_date')->nullable();
      $table->string('contract_number')->nullable();;
      $table->text('description')->nullable();
      $table->boolean('active')->default(true);
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
