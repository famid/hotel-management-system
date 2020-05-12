<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('user_name')->nullable()->unique();
            $table->string('password');
            $table->string('phone',25)->nullable();
            $table->integer('phone_verification_code')->nullable();
            $table->tinyInteger('is_phone_verified')->default(PENDING_STATUS);
            $table->string('address',191)->nullable();
            $table->string('zip_code',80)->nullable();
            $table->string('city',80)->nullable();
            $table->string('country',80)->nullable();
            $table->tinyInteger('role');
            $table->string('email_verification_code')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('language', 10)->nullable();
            $table->boolean('is_social_login')->default(false);
            $table->string('social_network_id', 180)->nullable();
            $table->string('social_network_type', 20)->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
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
