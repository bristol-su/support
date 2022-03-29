<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToLogicResultsAgainTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('logic_results', function (Blueprint $table) {
            $table->index(['logic_id', 'result'], 'log_res_logic_res_index');
            $table->index(['logic_id', 'user_id', 'result'], 'log_res_logic_res_user_index');
            $table->index(['logic_id', 'user_id', 'group_id', 'result'], 'log_res_logic_res_user_grp_index');
            $table->index(['logic_id', 'user_id', 'group_id', 'role_id', 'result'], 'logic_results_lugr_results_index');
        });

    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('logic_results', function(Blueprint $table) {
            $table->dropIndex('log_res_logic_res_index');
            $table->dropIndex('log_res_logic_res_user_index');
            $table->dropIndex('log_res_logic_res_user_grp_index');
            $table->dropIndex('logic_results_lugr_results_index');
        });
    }
}
