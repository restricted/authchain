<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->not_null();
                $table->string('name')->nullable();
                $table->string('firstname')->nullable();
                $table->string('surname')->nullable();
                $table->string('company')->nullable();
                $table->string('department')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->boolean('enabled');
                $table->text('password')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}