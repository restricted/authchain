<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateIpAddressesTable
 *
 * Example migration for Ip model
 */
class CreateIpAddressesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ip_addresses',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('address', 15);
                $table->unique('address');
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
        Schema::drop('ip_addresses');
    }

}
