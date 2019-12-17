<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleInstancePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_instance_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ability');
            $table->unsignedBigInteger('logic_id');
            $table->unsignedBigInteger('module_instance_id');
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
        Schema::dropIfExists('module_instance_permissions');
    }
}
