<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAddressPositionGroupRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_address_position_group', function (Blueprint $table) {
            $table->integer('position_group_id')->unsigned();
            $table->foreign('position_group_id')->references('id')->on('position_groups');
            $table->integer('company_address_id')->unsigned();
            $table->foreign('company_address_id')->references('id')->on('company_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_address_position_group');
    }
}