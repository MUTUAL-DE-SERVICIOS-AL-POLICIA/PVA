<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentToPositionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('position_groups', function (Blueprint $table) {
            $table->integer('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('position_groups', function (Blueprint $table) {
            $table->dropColumn(['document_id']);
        });
    }
}
