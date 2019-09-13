<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrynotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrynotes', function (Blueprint $table) {
            $table->bigIncrements('id');
          $table->unsignedInteger('providers_id');
          $table->foreign('providers_id')->references('id')->on('providers');
          $table->string('c31')->nullable();
          $table->string('bill1')->nullable();
          $table->string('bill2')->nullable();
          $table->string('reentry_state')->nullable();
          $table->string('reentry_date_c31')->nullable();
          $table->string('reentry_date_entrynote')->nullable();
          $table->string('reentry_date_bill')->nullable();
          $table->timestamps();
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

      Schema::table('providers', function(Blueprint $table){
        $table->dropForeign(['providers_id']);
      });
        Schema::dropIfExists('entrynotes');
    }
}
