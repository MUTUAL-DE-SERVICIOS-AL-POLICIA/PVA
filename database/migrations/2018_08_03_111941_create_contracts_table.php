<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('contracts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('employee_id')->unsigned();
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->integer('position_id')->unsigned();
			$table->foreign('position_id')->references('id')->on('positions');
			$table->integer('contract_type_id')->unsigned();
			$table->foreign('contract_type_id')->references('id')->on('contract_types');
			$table->integer('contract_mode_id')->unsigned();
			$table->foreign('contract_mode_id')->references('id')->on('contract_modes');
			$table->integer('retirement_reason_id')->nullable();
			$table->foreign('retirement_reason_id')->references('id')->on('retirement_reasons');
			$table->date('start_date');
			$table->date('end_date')->nullable();
			$table->date('retirement_date')->nullable();
			$table->boolean('status')->default(true);
			$table->string('rrhh_cite')->nullable();
			$table->date('rrhh_cite_date')->nullable();
			$table->string('performance_cite')->nullable();
			$table->string('insurance_number')->nullable();
			$table->string('contract_number')->nullable();
			$table->string('hiring_reference_number')->nullable();
			$table->text('description')->nullable();
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
		Schema::dropIfExists('contracts');
	}
}