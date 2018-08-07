<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerContributionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('employer_contributions', function (Blueprint $table) {
			$table->increments('id');
			$table->decimal('insurance_company', 5, 2)->default(0);
			$table->decimal('professional_risk', 5, 2)->default(0);
			$table->decimal('solidary', 5, 2)->default(0);
			$table->decimal('housing', 5, 2)->default(0);
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
		Schema::dropIfExists('employer_contributions');
	}
}
