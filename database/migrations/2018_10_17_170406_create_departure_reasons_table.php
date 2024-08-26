<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartureReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departure_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('departure_type_id')->nullable();
            $table->foreign('departure_type_id')->references('id')->on('departure_types');
            $table->string('name');
            $table->integer('day')->nullable();
            $table->integer('hour')->nullable();
            $table->string('each')->nullable();
            $table->boolean('pay')->default(true);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('departure_reasons');
    }
}
