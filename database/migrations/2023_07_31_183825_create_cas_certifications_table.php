<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cas_certifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('years');
            $table->integer('months');
            $table->integer('days');
            $table->string('certification_number');
            $table->date('issue_date');
            $table->boolean('active');
            $table->boolean('for_vacation');
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cas_certifications');
    }
}
