<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipContractTypeDepartureReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_type_departure_reason', function (Blueprint $table) {
            $table->integer('contract_type_id')->unsigned();
            $table->foreign('contract_type_id')->references('id')->on('contract_types');
            $table->integer('departure_reason_id')->unsigned();
            $table->foreign('departure_reason_id')->references('id')->on('departure_reasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_type_departure_reason');
    }
}