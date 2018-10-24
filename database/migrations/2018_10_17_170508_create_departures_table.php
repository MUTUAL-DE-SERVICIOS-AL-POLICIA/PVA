<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeparturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('departure_reason_id')->nullable();
            $table->foreign('departure_reason_id')->references('id')->on('departure_reasons');
            $table->string('destiny');
            $table->time('entry_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->time('return_time')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->text('desctiption')->nullable();
            $table->boolean('approved')->default(false);
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
        Schema::dropIfExists('departures');
    }
}
