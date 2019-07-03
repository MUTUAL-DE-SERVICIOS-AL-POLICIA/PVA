<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectorsDataToCompaniesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('companies', function (Blueprint $table) {
      $table->integer('directors_designation_number')->nullable();
      $table->date('directors_designation_date')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('companies', function (Blueprint $table) {
      $table->dropColumn(['directors_designation_number', 'directors_designation_date']);
    });
  }
}
