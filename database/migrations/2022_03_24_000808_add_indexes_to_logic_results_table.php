<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToLogicResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('logic_results', function (Blueprint $table) {
            $table->index(['logic_id']);
            $table->index(['user_id']);
            $table->index(['group_id']);
            $table->index(['role_id']);
            $table->index(['result']);
            $table->index(['logic_id', 'user_id']);
            $table->index(['logic_id', 'user_id', 'group_id']);
            $table->index(['logic_id', 'result']);
            $table->index(['logic_id', 'user_id', 'result']);
            $table->index(['logic_id', 'user_id', 'group_id', 'result']);
            $table->index(['logic_id', 'user_id', 'group_id', 'role_id', 'result']);
        });

    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('logic_results', function(Blueprint $table) {
            $table->dropIndex(['logic_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['group_id']);
            $table->dropIndex(['role_id']);
            $table->dropIndex(['result']);

            $table->dropIndex(['logic_id', 'user_id']);
            $table->dropIndex(['logic_id', 'user_id', 'group_id']);
            $table->dropIndex(['logic_id', 'result']);
            
            $table->dropIndex(['logic_id', 'user_id', 'result']);
            $table->dropIndex(['logic_id', 'user_id', 'group_id', 'result']);
            $table->dropIndex(['logic_id', 'user_id', 'group_id', 'role_id', 'result']);
        });
    }
}
