<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartureTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('departure_types', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('document_type_id')->nullable();
      $table->foreign('document_type_id')->references('id')->on('document_types');
      $table->string('name');
      $table->text('description');
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
    Schema::dropIfExists('departure_types');
  }
}
