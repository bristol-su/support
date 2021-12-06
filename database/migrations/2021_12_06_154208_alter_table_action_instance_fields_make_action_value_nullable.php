<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableActionInstanceFieldsMakeActionValueNullable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('action_instance_fields', function(Blueprint $table) {
            $table->text('action_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('action_instance_fields', function(Blueprint $table) {
            $table->string('action_value')->change();
        });
    }
}
