<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAddressesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('company_addresses', function (Blueprint $table) {
			$table->increments('id');
			$table->text('address');
			$table->tinyInteger('city_id')->unsigned();
			$table->foreign('city_id')->references('id')->on('cities');
			$table->boolean('active')->default(false);
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
		Schema::dropIfExists('company_addresses');
	}
}
