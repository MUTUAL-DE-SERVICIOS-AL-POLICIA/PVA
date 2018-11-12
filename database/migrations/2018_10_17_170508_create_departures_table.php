<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('contract_id')->nullable();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->integer('departure_reason_id')->nullable();
            $table->foreign('departure_reason_id')->references('id')->on('departure_reasons');
            $table->integer('certificate_id')->nullable();
            $table->foreign('certificate_id')->references('id')->on('certificates');
            $table->string('destiny');            
            $table->text('description')->nullable();
            $table->date('departure_date');
            $table->date('return_date');
            $table->time('departure_time')->nullable();
            $table->time('return_time')->nullable();
            $table->boolean('approved')->nullable()->default(null);
            $table->boolean('on_vacation')->default(false);
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
