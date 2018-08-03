<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('start_hour')->unsigned();
            $table->tinyInteger('start_minutes')->unsigned();
            $table->tinyInteger('end_hour')->unsigned();
            $table->tinyInteger('end_minutes')->unsigned();
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
        Schema::dropIfExists('job_schedules');
    }
}
