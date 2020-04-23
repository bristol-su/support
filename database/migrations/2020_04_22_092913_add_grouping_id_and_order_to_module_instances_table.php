<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupingIdAndOrderToModuleInstancesTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('module_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->nullable();
            $table->unsignedBigInteger('grouping_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_instances', function (Blueprint $table) {
            $table->dropColumn('order');
        });
        Schema::table('module_instances', function (Blueprint $table) {
            $table->dropColumn('grouping_id');
        });


    }
}
