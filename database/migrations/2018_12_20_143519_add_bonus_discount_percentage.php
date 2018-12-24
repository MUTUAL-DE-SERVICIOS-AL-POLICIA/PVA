<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBonusDiscountPercentage extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('bonus_procedures', function (Blueprint $table) {
      $table->unsignedTinyInteger('split_percentage')->nullable();
      $table->integer('limit_wage')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('bonus_procedures', function (Blueprint $table) {
      $table->dropColumn(['split_percentage', 'limit_wage']);
    });
  }
}
