<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveForVacationFromCasCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cas_certifications', function (Blueprint $table) {
            $table->dropColumn('for_vacation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cas_certifications', function (Blueprint $table) {
            $table->boolean('for_vacation')->default(false);
        });
    }
}
