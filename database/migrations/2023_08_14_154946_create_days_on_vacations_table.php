<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysOnVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days_on_vacations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->decimal('day', 5, 2);
            $table->integer('departure_id')->unsigned();
            $table->foreign('departure_id')->references('id')->on('departures');
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
        Schema::dropIfExists('days_on_vacations');
    }
}
