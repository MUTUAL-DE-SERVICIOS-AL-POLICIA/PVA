<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployerNumbersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('employer_numbers', function (Blueprint $table) {
      $table->increments('id');
      $table->string('number')->unique();
      $table->integer('company_id')->unsigned();
      $table->foreign('company_id')->references('id')->on('companies');
      $table->integer('insurance_company_id')->unsigned();
      $table->foreign('insurance_company_id')->references('id')->on('insurance_companies');
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
    Schema::dropIfExists('employer_numbers');
  }
}
