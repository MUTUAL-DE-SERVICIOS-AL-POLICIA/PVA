<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployerContributionIdToProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->integer('employer_tribute_id')->nullable();
            $table->foreign('employer_tribute_id')->references('id')->on('employer_tribute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedures', function (Blueprint $table) {
            //
        });
    }
}
