<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProceduresTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('procedures', function (Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('year')->default(Carbon::now()->year);
			$table->smallInteger('month_id')->default(Carbon::now()->month);
			$table->foreign('month_id')->references('id')->on('months');
			$table->integer('employee_discount_id');
			$table->foreign('employee_discount_id')->references('id')->on('employee_discounts');
			$table->integer('employer_contribution_id');
			$table->foreign('employer_contribution_id')->references('id')->on('employer_contributions');
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
		Schema::dropIfExists('procedures');
	}
}
