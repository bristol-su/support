<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionInstanceFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('action_instance_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_field');
            $table->string('action_field');
            $table->unsignedBigInteger('action_instance_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::dropIfExists('action_instance_fields');
    }
}
