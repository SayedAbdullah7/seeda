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
        Schema::create('notify', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id')->onDelete('cascade');;
            $table->foreign('notification_id')->references('id')->on('notifications')->cascadeOnDelete();

            $table->unsignedBigInteger('user_id')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->boolean('is_seen')->default(0);


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
        Schema::dropIfExists('notify');
    }
};
