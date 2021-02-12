<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressChangeHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() :void
    {
        Schema::create('progress_change_hashes', function (Blueprint $table) {
            $table->string('item_key')->primary();
            $table->string('hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() :void
    {
        Schema::drop('progress_change_hashes');
    }
}
