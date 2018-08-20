<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('employees', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->tinyInteger('city_identity_card_id')->unsigned();
			$table->foreign('city_identity_card_id')->references('id')->on('cities');
			$table->integer('management_entity_id')->unsigned()->nullable();
			$table->foreign('management_entity_id')->references('id')->on('management_entities');
			$table->string('identity_card');
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('mothers_last_name')->nullable();
			$table->date('birth_date')->nullable();
			$table->tinyInteger('city_birth_id')->unsigned()->nullable();
			$table->foreign('city_birth_id')->references('id')->on('cities');
			$table->string('account_number')->nullable();
			$table->string('country_birth')->default('BOLIVIA');
			$table->string('nua_cua')->nullable();
			$table->enum('gender', ['M', 'F'])->nullable();
			$table->string('location')->nullable();
			$table->string('zone')->nullable();
			$table->string('street')->nullable();
			$table->string('address_number')->nullable();
			$table->integer('phone_number')->nullable();
			$table->boolean('active')->default(1);
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
		Schema::dropIfExists('employees');
	}
}
