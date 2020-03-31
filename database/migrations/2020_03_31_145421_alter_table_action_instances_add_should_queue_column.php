<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableActionInstancesAddShouldQueueColumn extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('action_instances', function (Blueprint $table) {
            $table->boolean('should_queue')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_instances', function (Blueprint $table) {
            $table->dropColumn('should_queue');
        });    
    }
}
