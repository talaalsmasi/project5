<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLessorsTable extends Migration
{
    public function up()
    {
        Schema::table('lessors', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
        });
    }

    public function down()
    {
        Schema::table('lessors', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'password']);
        });
    }
}