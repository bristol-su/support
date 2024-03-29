<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKeyColumnNotUniqueOnSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
    }
}
