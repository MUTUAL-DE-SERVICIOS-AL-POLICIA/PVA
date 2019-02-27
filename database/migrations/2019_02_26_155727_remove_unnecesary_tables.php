<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecesaryTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('departures', function (Blueprint $table) {
      $table->dropColumn(['certificate_id', 'destiny', 'contract_id']);
      $table->integer('employee_id')->nullable();
      $table->foreign('employee_id')->references('id')->on('employees');
    });
    Schema::dropIfExists('certificates');
    Schema::table('departure_reasons', function (Blueprint $table) {
      $table->dropColumn(['departure_type_id']);
      $table->boolean('note')->default(0);
      $table->boolean('description_needed')->default(1);
      $table->renameColumn('hour', 'hours');
      $table->renameColumn('day', 'days');
      $table->renameColumn('each', 'reset');
      $table->renameColumn('pay', 'payable');
    });
    Schema::dropIfExists('departure_types');
    Schema::table('companies', function (Blueprint $table) {
      $table->dropColumn(['document_id']);
    });
    Schema::table('position_groups', function (Blueprint $table) {
      $table->dropColumn(['document_id']);
    });
    Schema::dropIfExists('documents');
    Schema::dropIfExists('document_types');
    Schema::dropIfExists('contract_type_departure_reason');
    Schema::table('employer_tributes', function (Blueprint $table) {
      $table->dropColumn(['ufv']);
      $table->renameColumn('minimum_salary', 'value');
    });
    Schema::rename('employer_tributes', 'minimum_salaries');
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['remember_token']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->text('remember_token')->nullable();
    });
    Schema::rename('minimum_salaries', 'employer_tributes');
    Schema::table('employer_tributes', function (Blueprint $table) {
      $table->decimal('ufv', 7, 5)->default(0);
      $table->renameColumn('value', 'minimum_salary');
    });
    Schema::create('contract_type_departure_reason', function (Blueprint $table) {
      $table->integer('contract_type_id')->unsigned();
      $table->foreign('contract_type_id')->references('id')->on('contract_types');
      $table->integer('departure_reason_id')->unsigned();
      $table->foreign('departure_reason_id')->references('id')->on('departure_reasons');
    });
    Schema::create('document_types', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->unique();
      $table->string('shortened')->nullable();
      $table->timestamps();
    });
    Schema::create('documents', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('document_type_id')->unsigned();
      $table->foreign('document_type_id')->references('id')->on('document_types');
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
    Schema::table('position_groups', function (Blueprint $table) {
      $table->integer('document_id')->nullable();
      $table->foreign('document_id')->references('id')->on('documents');
    });
    Schema::table('companies', function (Blueprint $table) {
      $table->integer('document_id')->nullable();
      $table->foreign('document_id')->references('id')->on('documents');
    });
    Schema::create('departure_types', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('document_type_id')->nullable();
      $table->foreign('document_type_id')->references('id')->on('document_types');
      $table->string('name');
      $table->text('description');
      $table->timestamps();
    });
    Schema::table('departure_reasons', function (Blueprint $table) {
      $table->integer('departure_type_id')->nullable();
      $table->foreign('departure_type_id')->references('id')->on('departure_types');
      $table->dropColumn(['note', 'description_needed']);
      $table->renameColumn('hours', 'hour');
      $table->renameColumn('days', 'day');
      $table->renameColumn('reset', 'each');
      $table->renameColumn('payable', 'pay');
    });
    Schema::create('certificates', function (Blueprint $table) {
      $table->increments('id');
      $table->smallInteger('document_type_id');
      $table->foreign('document_type_id')->references('id')->on('document_types');
      $table->integer('correlative');
      $table->integer('year');
      $table->json('data');
      $table->timestamps();
    });
    Schema::table('departures', function (Blueprint $table) {
      $table->dropColumn(['employee_id']);
      $table->integer('certificate_id')->nullable();
      $table->foreign('certificate_id')->references('id')->on('certificates');
      $table->string('destiny')->nullable();
      $table->integer('contract_id')->nullable();
      $table->foreign('contract_id')->references('id')->on('contracts');
    });
  }
}
