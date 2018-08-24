<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('payrolls', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('code');
			$table->tinyInteger('unworked_days')->default(0);
			$table->bigInteger('procedure_id');
			$table->foreign('procedure_id')->references('id')->on('procedures');
			$table->bigInteger('contract_id');
			$table->foreign('contract_id')->references('id')->on('contracts');
			$table->bigInteger('charge_id');
			$table->foreign('charge_id')->references('id')->on('charges');
			$table->bigInteger('position_group_id');
			$table->foreign('position_group_id')->references('id')->on('position_groups');
			$table->bigInteger('position_id');
			$table->foreign('position_id')->references('id')->on('positions');
			$table->decimal('faults', 7, 2)->default(0);
			$table->decimal('next_month_balance', 7, 2)->default(0);
			$table->decimal('previous_month_balance', 7, 2)->default(0);
			$table->decimal('rc_iva', 7, 2)->default(0);
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
		Schema::dropIfExists('payrolls');
	}
}
