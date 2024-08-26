<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameAndPositionToUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropUnique(['username']);
      $table->integer('employee_id')->nullable();
      $table->foreign('employee_id')->references('id')->on('employees');
      $table->string('position')->nullable();
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
      $table->unique('username');
      $table->dropColumn('employee_id');
      $table->dropColumn('position');
    });
  }
}
