<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReflowNationalOnEmployeeDiscountsTable extends Migration
{
  /**
    * Run the migrations.
    *
    * @return void
    */
  public function up()
  {
    Schema::table('employee_discounts', function (Blueprint $table) {
      $table->dropColumn(['national']);
      $table->json('national_limits')->default('[1]');
      $table->json('national_percentages')->default('[1]');
    });
    Schema::table('minimum_salaries', function (Blueprint $table) {
      $table->dropColumn(['limits', 'percentages']);
    });
  }

  /**
    * Reverse the migrations.
    *
    * @return void
    */
  public function down()
  {
    Schema::table('employee_discounts', function (Blueprint $table) {
      $table->dropColumn(['national_limits', 'national_percentages']);
      $table->decimal('national', 7, 5)->default(0);
    });
    Schema::table('minimum_salaries', function (Blueprint $table) {
      $table->json('limits')->default('[1]');
      $table->json('percentages')->default('[1]');
    });
  }
}