<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultantPayrollsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('consultant_payrolls', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('code');
      $table->tinyInteger('unworked_days')->default(0);
      $table->bigInteger('consultant_procedure_id');
      $table->foreign('consultant_procedure_id')->references('id')->on('consultant_procedures');
      $table->bigInteger('consultant_contract_id');
      $table->foreign('consultant_contract_id')->references('id')->on('consultant_contracts');
      $table->bigInteger('employee_id');
      $table->foreign('employee_id')->references('id')->on('employees');
      $table->bigInteger('charge_id');
      $table->foreign('charge_id')->references('id')->on('charges');
      $table->bigInteger('position_group_id');
      $table->foreign('position_group_id')->references('id')->on('position_groups');
      $table->bigInteger('consultant_position_id');
      $table->foreign('consultant_position_id')->references('id')->on('consultant_positions');
      $table->decimal('faults', 7, 2)->default(0);
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
    Schema::dropIfExists('consultant_payrolls');
  }
}
