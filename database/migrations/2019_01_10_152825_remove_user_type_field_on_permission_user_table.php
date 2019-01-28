<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUserTypeFieldOnPermissionUserTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('permission_user', function (Blueprint $table) {
      $table->dropColumn(['user_type']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('permission_user', function (Blueprint $table) {
      $table->string('user_type')->primary();
    });
  }
}
