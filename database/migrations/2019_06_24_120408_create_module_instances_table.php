<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('module_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias');
            $table->unsignedInteger('activity_id');
            $table->string('slug');
            $table->unsignedBigInteger('completion_condition_instance_id')->nullable();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('active');
            $table->unsignedInteger('visible');
            $table->unsignedInteger('mandatory')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::dropIfExists('module_instances');
    }
}
