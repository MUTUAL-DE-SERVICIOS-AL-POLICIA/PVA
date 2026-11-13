<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationTimeTable extends Migration
{
    public function up()
    {
        Schema::create('vacation_time', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->decimal('day',5,2);
            $table->enum('period', ['MAÑANA', 'TARDE'])->nullable();
            $table->integer('departure_id');
            $table->unsignedBigInteger('vacation_queue_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('departure_id')
                ->references('id')
                ->on('departures');
            $table->foreign('vacation_queue_id')
                ->references('id')
                ->on('vacation_queues');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vacation_time');
    }
}