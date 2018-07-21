<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('employees', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('insurance_company_id')->unsigned();
      $table->foreign('insurance_company_id')->references('id')->on('insurance_companies');
      $table->tinyInteger('city_identity_card_id')->unsigned();
      $table->foreign('city_identity_card_id')->references('id')->on('cities');
      $table->integer('management_entity_id')->unsigned();
      $table->foreign('management_entity_id')->references('id')->on('management_entities');
      $table->string('identity_card');
      $table->string('first_name');
      $table->string('second_name')->nullable();
      $table->string('last_name')->nullable();
      $table->string('mothers_last_name')->nullable();
      $table->string('surname_husband')->nullable();
      $table->date('birth_date')->nullable();
      $table->tinyInteger('city_birth_id')->unsigned();
      $table->foreign('city_birth_id')->references('id')->on('cities');
      $table->string('account_number')->nullable();
      $table->string('country_birth')->nullable();
      $table->string('nua_cua')->nullable();
      $table->enum('gender', ['M', 'F'])->nullable();
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
    Schema::dropIfExists('employees');
  }
}
