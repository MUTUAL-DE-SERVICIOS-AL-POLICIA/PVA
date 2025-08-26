<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssistantContractIdToContractJobSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_job_schedule', function (Blueprint $table) {
          $table->integer('assistant_contract_id')->nullable();
          $table->foreign('assistant_contract_id')->references('id')->on('assistant_contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_job_schedule', function (Blueprint $table) {
          $table->integer('assistant_contract_id');
        });
    }
}
