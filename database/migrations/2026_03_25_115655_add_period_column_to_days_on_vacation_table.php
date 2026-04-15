<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodColumnToDaysOnVacationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('days_on_vacations', function (Blueprint $table) {
            $table->enum('period', ['mañana', 'tarde', 'todo el día'])->after('departure_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('days_on_vacations', function (Blueprint $table) {
            $table->dropColumn('period');
        });
    }
}
