<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('cities', function (Blueprint $table) {
			$table->tinyIncrements('id');
			$table->string('name')->unique();
			$table->integer('employer_number_id')->unsigned()->nullable();
			$table->foreign('employer_number_id')->references('id')->on('employer_numbers');
			$table->string('shortened')->unique();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('cities');
	}
}
