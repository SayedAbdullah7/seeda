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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('shipment_id')->nullable();

            $table->unsignedBigInteger('shipment_type_id')->onDelete('cascade');;
            $table->foreign('shipment_type_id')->references('id')->on('shipment_type')->cascadeOnDelete();

            $table->string('status')->nullable();


            $table->timestamps();
            $table->string('appKey');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
