<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToActivitiesAndModuleInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_instances', function (Blueprint $table) {
            $table->text('image_url')->nullable();
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->text('image_url')->nullable();
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
            $table->dropColumn('image_url');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
}
