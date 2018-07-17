<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAccountNumbersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('company_account_numbers', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('account_number')->unique();
      $table->string('financial_entity');
      $table->text('description');
      $table->integer('company_id')->unsigned();
      $table->timestamps();
      $table->foreign('company_id')->references('id')->on('companies');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('company_account_numbers');
  }
}
