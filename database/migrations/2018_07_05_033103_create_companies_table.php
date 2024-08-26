<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('companies', function (Blueprint $table) {
			$table->increments('id');
			$table->text('name');
			$table->string('shortened');
			$table->bigInteger('tax_number');
			$table->integer('document_id')->nullable();
			$table->foreign('document_id')->references('id')->on('documents');
			$table->timestamps();
			$table->unique('name');
			$table->unique('shortened');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('companies');
	}
}
