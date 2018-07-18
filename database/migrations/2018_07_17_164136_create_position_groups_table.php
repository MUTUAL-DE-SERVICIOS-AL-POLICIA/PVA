<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionGroupsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('position_groups', function (Blueprint $table) {
      $table->increments('id');
      $table->text('name');
      $table->string('shortened');
      $table->tinyInteger('company_address_id')->unsigned();
      $table->foreign('company_address_id')->references('id')->on('company_addresses');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('position_groups');
  }
}
