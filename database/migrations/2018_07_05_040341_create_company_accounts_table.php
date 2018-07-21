<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAccountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('company_accounts', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('account')->unique();
      $table->string('financial_entity');
      $table->text('description');
      $table->integer('company_id')->unsigned();
      $table->foreign('company_id')->references('id')->on('companies');
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
    Schema::dropIfExists('company_accounts');
  }
}
