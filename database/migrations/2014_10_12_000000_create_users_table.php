<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('birth')->nullable();
            $table->string('nickName')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();;
            $table->boolean('is_active')->default(1);
            $table->boolean('is_verified')->default(1);
            $table->tinyInteger("is_online")->default(1);
            $table->tinyInteger("is_approved")->default(1);
            $table->string('otp')->nullable();
            $table->datetime('last_otp_sent')->nullable(1);
            $table->timestamp("last_activity")->nullable();
            $table->unsignedBigInteger("country_id")->nullable();
            $table->string('appKey');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(["email","appKey"]);
            $table->unique(["phone","appKey"]);

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
};
