<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsultantContractJobScheduleRelationship extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('contract_job_schedule', function (Blueprint $table) {
      $table->integer('contract_id')->nullable()->change();
      $table->integer('consultant_contract_id')->unsigned()->nullable();
      $table->foreign('consultant_contract_id')->references('id')->on('consultant_contracts');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('contract_job_schedule', function (Blueprint $table) {
      $table->integer('contract_id')->nullable()->change();
      $table->dropColumn('consultant_contract_id');
    });
  }
}
