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
        Schema::create('payment_transcations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->bigInteger("order_id");
            $table->string("status");
            $table->double("amount",8,2);
            $table->tinyInteger("mobile_wallet")->default(0);
            $table->tinyInteger("card")->default(0);
            $table->tinyInteger("is_wallet")->default(0);
            $table->tinyInteger("is_order")->default(0);
            $table->string("appKey");
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
        Schema::dropIfExists('payment_transcations');
    }
};
