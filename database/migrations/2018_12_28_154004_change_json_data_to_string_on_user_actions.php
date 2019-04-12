<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJsonDataToStringOnUserActions extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_actions', function (Blueprint $table) {
      $table->dropColumn(['method', 'path', 'data']);
      $table->text('action')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('user_actions', function (Blueprint $table) {
      $table->string('method')->nullable();
      $table->string('path')->nullable();
      $table->json('data')->nullable();
      $table->dropColumn(['action']);
    });
  }
}
