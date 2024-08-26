<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusProceduresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('bonus_procedures', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedSmallInteger('year');
      $table->foreign('year')->references('year')->on('bonus_years')->onUpdate('cascade')->onDelete('cascade');
      $table->date('pay_date');
      $table->string('name')->default('AGUINALDO DE NAVIDAD');
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
    Schema::dropIfExists('bonus_procedures');
  }
}
