<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionGroupsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('position_groups', function (Blueprint $table) {
			$table->increments('id');
			$table->text('name');
			$table->string('shortened');
			$table->integer('document_id')->nullable();
			$table->foreign('document_id')->references('id')->on('documents');
			$table->integer('company_address_id')->unsigned();
			$table->foreign('company_address_id')->references('id')->on('company_addresses');
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
		Schema::dropIfExists('position_groups');
	}
}
