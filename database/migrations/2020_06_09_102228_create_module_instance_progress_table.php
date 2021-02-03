<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleInstanceProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('module_instance_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('module_instance_id');
            $table->unsignedBigInteger('progress_id');
            $table->boolean('mandatory');
            $table->boolean('complete');
            $table->boolean('active');
            $table->boolean('visible');
            $table->unsignedDecimal('percentage')->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::drop('module_instance_progress');
    }
}
