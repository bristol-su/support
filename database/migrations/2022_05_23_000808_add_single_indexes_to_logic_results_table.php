<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSingleIndexesToLogicResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('logic_results', function (Blueprint $table) {
            $table->index(['user_id'], 'log_res_user_index');
            $table->index(['group_id'], 'log_res_grp_index');
            $table->index(['role_id'], 'log_res_role_index');
        });

    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('logic_results', function(Blueprint $table) {
            $table->dropIndex('log_res_user_index');
            $table->dropIndex('log_res_grp_index');
            $table->dropIndex('log_res_role_index');
        });
    }
}
