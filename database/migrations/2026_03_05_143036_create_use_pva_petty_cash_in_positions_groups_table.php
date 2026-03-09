<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsePvaPettyCashInPositionsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('position_groups', function (Blueprint $table) {
          $table->boolean('pva_petty_cash')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('position_groups', function (Blueprint $table) {
            $table->dropColumn('pva_petty_cash');
        });
    }
}
