<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateToVacationQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vacation_queues', function (Blueprint $table) {
            $table->text('comment')->nullable()->after('Comentar motivo de eliminación');
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
        Schema::table('vacation_queues', function (Blueprint $table) {
            $table->dropColumn('comment');
            $table->dropSoftDeletes();
        });
    }
}
