<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('positions', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('item')->unsigned();
			$table->integer('charge_id')->unsigned();
			$table->foreign('charge_id')->references('id')->on('charges');
			$table->integer('position_group_id')->unsigned();
			$table->foreign('position_group_id')->references('id')->on('position_groups');
			$table->boolean('active')->default(true);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('positions');
	}
}
