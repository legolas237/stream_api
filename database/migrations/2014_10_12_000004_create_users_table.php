<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar');

            $table->bigInteger('user_detail_id')->unsigned();
            $table->foreign('user_detail_id')->references('id')->on('user_details');
            $table->bigInteger('device_id')->unsigned()->nullable();
            $table->foreign('device_id')->references('id')->on('devices');
            $table->bigInteger('phone_code_id')->unsigned()->nullable();
            $table->foreign('phone_code_id')->references('id')->on('phone_codes');

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
