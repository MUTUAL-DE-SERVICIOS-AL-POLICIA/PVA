<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeniorityBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seniority_bonuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('from', 5, 2);
            $table->decimal('to', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->boolean('active');
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
        Schema::dropIfExists('seniority_bonuses');
    }
}
