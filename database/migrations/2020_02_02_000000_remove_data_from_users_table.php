<?php

use BristolSU\Support\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDataFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('forename');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('forename')->nullable();
            $table->string('surname')->nullable();
            $table->string('student_id')->nullable();
            $table->string('email')->unique()->nullable();
        });
    }
}
