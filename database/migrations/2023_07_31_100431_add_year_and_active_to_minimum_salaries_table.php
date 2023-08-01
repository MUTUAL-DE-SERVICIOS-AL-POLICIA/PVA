<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearAndActiveToMinimumSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('minimum_salaries', function (Blueprint $table) {
            $table->integer('year')->nullable();
            $table->boolean('active')->nullable();
            $table->dropColumn('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('minimum_salaries', function (Blueprint $table) {
            $table->dropColumn(['year']);
            $table->dropColumn(['active']);
            $table->timestamp('updated_at');
        });
    }
}
