<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSchedulesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('job_schedules', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('start_hour')->unsigned();
			$table->tinyInteger('start_minutes')->unsigned();
			$table->tinyInteger('end_hour')->unsigned();
			$table->tinyInteger('end_minutes')->unsigned();
			$table->json('workdays')->default('[1,2,3,4,5]');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('job_schedules');
	}
}
