<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleInstanceServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('module_instance_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('service');
            $table->unsignedInteger('module_instance_id');
            $table->unsignedInteger('connection_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::dropIfExists('module_instance_services');
    }
}
