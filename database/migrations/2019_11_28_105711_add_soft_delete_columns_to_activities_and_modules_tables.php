<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteColumnsToActivitiesAndModulesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('activity_instances', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('module_instances', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('module_instance_settings', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('module_instance_permissions', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('action_instances', function(Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('action_instance_fields', function(Blueprint $table) {
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
        Schema::table('activities', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('activity_instances', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('module_instances', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });


        Schema::table('module_instance_settings', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('module_instance_permissions', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('action_instances', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('action_instance_fields', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
