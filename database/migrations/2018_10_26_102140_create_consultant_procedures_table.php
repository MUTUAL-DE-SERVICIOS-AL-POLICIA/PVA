<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultantProceduresTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consultant_procedures', function (Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('year')->default(Carbon::now()->year);
			$table->smallInteger('month_id')->default(Carbon::now()->month);
			$table->foreign('month_id')->references('id')->on('months');
			$table->date('pay_date')->nullable();
			$table->decimal('ufv', 7, 5)->default(0);
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
	public function down()
	{
		Schema::dropIfExists('consultant_procedures');
	}
}
