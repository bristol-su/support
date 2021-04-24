<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToModuleInstanceGrouping extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('module_instance_grouping', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('module_instance_grouping', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
