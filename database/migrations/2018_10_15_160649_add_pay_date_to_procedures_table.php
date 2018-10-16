<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayDateToProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->date('pay_date')->nullable();
            $table->decimal('ufv', 7, 5)->default(0);
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
            $table->dropColumn('pay_date');
            $table->dropColumn('ufv');
        });
    }
}
