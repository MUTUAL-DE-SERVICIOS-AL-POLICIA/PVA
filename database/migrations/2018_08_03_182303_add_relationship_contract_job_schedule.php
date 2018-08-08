<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipContractJobSchedule extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('contract_job_schedule', function (Blueprint $table) {
			$table->integer('contract_id')->unsigned();
			$table->foreign('contract_id')->references('id')->on('contracts');
			$table->integer('job_schedule_id')->unsigned();
			$table->foreign('job_schedule_id')->references('id')->on('job_schedules');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('contract_job_schedule');
	}
}
