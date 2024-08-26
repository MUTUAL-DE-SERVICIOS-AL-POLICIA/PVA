<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDiscountsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('employee_discounts', function (Blueprint $table) {
			$table->increments('id');
			$table->decimal('elderly', 7, 5)->default(0);
			$table->decimal('common_risk', 7, 5)->default(0);
			$table->decimal('comission', 7, 5)->default(0);
			$table->decimal('solidary', 7, 5)->default(0);
			$table->decimal('national', 7, 5)->default(0);
			$table->decimal('rc_iva', 7, 5)->default(0);
			$table->boolean('active');
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
		Schema::dropIfExists('employee_discounts');
	}
}
