<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_permissions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ability');
            $table->string('model');
            $table->unsignedInteger('model_id');
            $table->unsignedBigInteger('module_instance_id')->nullable()->default(null);
            $table->boolean('result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_permissions');
    }
}
