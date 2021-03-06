<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('action_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('action_instance_id');
            $table->json('event_fields');
            $table->json('settings');
            $table->string('message');
            $table->boolean('success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::dropIfExists('action_histories');
    }
}
