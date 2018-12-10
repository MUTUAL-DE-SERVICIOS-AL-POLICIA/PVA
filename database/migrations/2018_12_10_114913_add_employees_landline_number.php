<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeesLandlineNumber extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->integer('landline_number')->nullable();
      $table->integer('phone_number')->nullable(false)->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->integer('phone_number')->nullable(true)->change();
      $table->dropColumn('landline_number');
    });
  }
}
