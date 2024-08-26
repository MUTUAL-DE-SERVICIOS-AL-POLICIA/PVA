<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLivingExpensesToEmployerContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employer_contributions', function (Blueprint $table) {
            $table->float('living_expenses', 5, 2)->default(18);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employer_contributions', function (Blueprint $table) {
            $table->dropColumn(['living_expenses']);
        });
    }
}
