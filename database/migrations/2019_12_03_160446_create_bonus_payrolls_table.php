<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bonus_procedure_id')->unsigned();
			$table->foreign('bonus_procedure_id')->references('id')->on('bonus_procedures');
            $table->bigInteger('contract_id')->unsigned();
			$table->foreign('contract_id')->references('id')->on('contracts');
            $table->date('start_date');
			$table->date('end_date');
            $table->tinyInteger('months')->unsigned();
            $table->float('days', 16, 14);
            $table->json('wages');
            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('bonus_payrolls');
    }
}